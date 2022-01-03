const mongoose = require("mongoose");

class Ingredients extends mongoose.model("Ingredients", new mongoose.Schema({
    ingredient: {
        type: String,
        required: true,
        unique: true,
        lowercase: true
    }
}, {strict: "throw", versionKey: false})) {
}

class Tags extends mongoose.model("Tags", new mongoose.Schema({
    tag: {
        type: String,
        required: true,
        unique: true,
        lowercase: true
    }
}, {strict: "throw", versionKey: false})) {
}

class Recipes extends mongoose.model("Recipes", new mongoose.Schema({
    title: {
        type: String,
        required: true
    },
    description: {
        type: String,
        required: true
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
                        required: true
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
        required: true
    },
    instructions: [{
        type: {
            "instruction": {
                type: String,
                required: true
            },
        },
        required: true
    }],
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
        options: {versionKey: false}
    }
}, {strict: "throw", versionKey: false})) {
    static search = async (searchString, options) => {
        // noinspection JSCheckFunctionSignatures
        const tags = Array.from(searchString.matchAll(/(?<=#)(\w+)/gu)).map(match => match[1]);
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
                ...words.map(word => ({"title": new RegExp(word)}))
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
        }, options): null;

        return {
            relevantSearch: relevantQuery, titleSearch: titleQuery, ingredientSearch: ingredientQuery
        };
    }
}

module.exports = {
    Ingredients: Ingredients,
    Tags: Tags,
    Recipes: Recipes
};