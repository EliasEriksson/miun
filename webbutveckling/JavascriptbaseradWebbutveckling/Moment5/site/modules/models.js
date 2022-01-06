const mongoose = require("mongoose");

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

    static search = async (searchString, options) => {
        // noinspection JSCheckFunctionSignatures
        const tags = Array.from(searchString.matchAll(/(?<=#)([\w-]+)/gu)).map(match => match[1]);
        // noinspection JSCheckFunctionSignatures
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

        let [qTags, qIngredients] = await Promise.all([qTagsP, qIngredientsP]);
        qTags = qTags ?? [];
        qIngredients = qIngredients ?? [];

        const relevantQuery = qTags.length || qIngredients.length || words.length ? await Recipes.paginate({
            $and: [
                ...qTags.map(qTag => ({"tags.tag": qTag.id})),
                ...qIngredients.map(qIngredients => ({"ingredients.ingredient": qIngredients._id})),
                ...words.map(word => ({"title": new RegExp(word, "i")}))
            ]
        }, options) : null;

        const ingredientQuery = qTags.length || qIngredients.length ? await Recipes.paginate({
            $and: [
                ...qTags.map(qTag => ({"tags.tag": qTag.id})),
                ...qIngredients.map(qIngredients => ({"ingredients.ingredient": qIngredients._id}))
            ]
        }, options) : null;

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

    static capitalize = (str) => {
        return str.replace(/^\w|[!?.]\s\w/gu, (txt, extra) => {
            if (!extra) {
                return txt.charAt(0).toUpperCase() + txt.substring(1).toLowerCase();
            }
            return txt.substring(0, 2).toLowerCase() + txt.charAt(2).toUpperCase();
        });
    }

    static findAndUpdate = async (id, data) => {
        Recipes.format(data);
        return await Recipes.findByIdAndUpdate(id, data, {runValidators: true})
    }

    validate = async (callback) => {
        await super.validate();
        Recipes.format(this);
        if (callback) {
            await callback()
        }
    }

    save = async (callback) => {
        await this.validate()
        await super.save();
        if (callback) {
            await callback();
        }
    }
}

module.exports = {
    Ingredients: Ingredients,
    Tags: Tags,
    Recipes: Recipes
};