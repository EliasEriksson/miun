const mongoose = require("mongoose");

/**
 * the ingredient model
 *
 * the model is extended in case more functionality needs to be added.
 */
class Ingredients extends mongoose.model("Ingredients", new mongoose.Schema({
    ingredient: {
        type: String,
        required: true,
        unique: true,
        lowercase: true,
        validate: value => value
    }
}, {strict: "throw", versionKey: false})) {
}

/**
 * the tag model
 *
 * the model is extended in case more functionality needs to be added.
 */
class Tags extends mongoose.model("Tags", new mongoose.Schema({
    tag: {
        type: String,
        required: true,
        unique: true,
        lowercase: true,
        validate: value => value
    }
}, {strict: "throw", versionKey: false})) {
}

/**
 * the recipe model
 */
class Recipes extends mongoose.model("Recipes", new mongoose.Schema({
    title: {
        type: String,
        required: true,
        validate: value => value
    },
    description: {
        type: String,
        required: true,
        validate: value => value
    },
    ingredients: {
        type: [
            {
                type: {
                    "ingredient": {
                        type: mongoose.Schema.Types.ObjectId,
                        ref: "Ingredients",
                        required: true
                    },
                    "amount": {
                        type: Number,
                        required: true,
                        validate: value => value > 0
                    },
                    "unit": {
                        type: String,
                        enum: [
                            "ml", "cl", "dl", "l",
                            "g", "kg", "st"
                        ],
                        required: true
                    }
                },
                required: true,
                unique: true
            }
        ],
        required: true,
    },
    instructions: [
        {
            type: {
                "instruction": {
                    type: String,
                    required: true,
                    validate: value => value
                },
            },
            required: true,
        }
    ],
    tags: {
        type: [
            {
                type: {
                    "tag": {
                        type: mongoose.Schema.Types.ObjectId,
                        ref: "Tags"
                    }
                },
                required: true,
                unique: true
            }
        ],
        options: {versionKey: false},
        required: true,
    }
}, {strict: "throw", versionKey: false})) {

    /**
     * allows the model to reformat text to capitalized text automatically.
     *
     * data follows the same structure as the schema.
     */
    static format = (data) => {
        if (data.title) {
            data.title = Recipes.capitalize(data.title.toLowerCase());
        }
        if (data.description) {
            data.description = Recipes.capitalize(data.description.toLowerCase());
        }
        if (data.instructions) {
            data.instructions.forEach(
                instructionObj => {
                    instructionObj.instruction = Recipes.capitalize(
                        instructionObj.instruction.toLowerCase()
                    )
                }
            );
        }
    }

    /**
     * searches the database of recipes with the search string.
     *
     * the options are applied to the mongoose query.
     */
    static search = async (searchString, options) => {
        // finds all the tags in the search string
        const tags = Array.from(searchString.matchAll(/(?<=#)([\w-]+)/gu)).map(match => match[1]);
        // finds all the words that are not tags
        const words = Array.from(searchString.matchAll(/(?:^|\s)(\w+)/gu)).map(match => match[1]);

        let qTagsP;
        let qIngredientsP;

        if (tags.length) {
            qTagsP = Tags.find({
                $or: tags.map(tag => ({
                    "tag": tag
                }))
            });
        }

        if (words.length) {
            qIngredientsP = Ingredients.find({
                $or: words.map(ingredient => ({
                    "ingredient": ingredient
                }))
            });
        }

        // awaiting the promises here to prevent waiting for as much IO.
        let [qTags, qIngredients] = await Promise.all([qTagsP, qIngredientsP]);
        qTags = qTags ?? [];
        qIngredients = qIngredients ?? [];

        // search with tags, ingredients and title
        const relevantQuery = qTags.length || qIngredients.length || words.length ? await Recipes.paginate({
            $and: [
                ...qTags.map(qTag => ({"tags.tag": qTag.id})),
                ...qIngredients.map(qIngredients => ({"ingredients.ingredient": qIngredients._id})),
                ...words.map(word => ({"title": new RegExp(word, "i")}))
            ]
        }, options) : null;

        // search with tags and ingredients
        const ingredientQuery = qTags.length || qIngredients.length ? await Recipes.paginate({
            $and: [
                ...qTags.map(qTag => ({"tags.tag": qTag.id})),
                ...qIngredients.map(qIngredients => ({"ingredients.ingredient": qIngredients._id}))
            ]
        }, options) : null;

        // search with tags and title.
        const titleQuery = qTags.length || words.length ? await Recipes.paginate({
            $and: [
                ...qTags.map(qTag => ({"tags.tag": qTag.id})),
                ...words.map(word => ({"title": new RegExp(word)}))
            ]
        }, options) : null;

        return {
            relevantSearch: relevantQuery, titleSearch: titleQuery, ingredientSearch: ingredientQuery
        };
    }

    /**
     * capitalizes a string.
     *
     * follows punctuation rules.
     */
    static capitalize = (str) => {
        return str.replace(/^\w|[!?.]\s\w/gu, (txt, extra) => {
            if (!extra) {
                return txt.charAt(0).toUpperCase() + txt.substring(1).toLowerCase();
            }
            return txt.substring(0, 2).toLowerCase() + txt.charAt(2).toUpperCase();
        });
    }

    /**
     * wrapper function for findByIdAndUpdate.
     *
     * sine findByIdAndUpdate is static it can not be overwritten.
     * data has the same structure as the schema.
     */
    static findAndUpdate = async (id, data) => {
        Recipes.format(data);
        return await Recipes.findByIdAndUpdate(id, data, {runValidators: true})
    }

    /**
     * formats the data after it is validated.
     */
    validate = async (callback) => {
        await super.validate();
        Recipes.format(this);
        if (callback) {
            await callback()
        }
    }

    /**
     * validates the data before its saved.
     *
     * validating the data causes it to be formatted.
     */
    save = async (callback) => {
        await this.validate()
        await super.save();
        if (callback) {
            await callback();
        }
    }
}

/**
 * exporting the models.
 */
module.exports = {
    Ingredients: Ingredients,
    Tags: Tags,
    Recipes: Recipes
};