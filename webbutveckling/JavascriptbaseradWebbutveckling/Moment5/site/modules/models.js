const mongoose = require("mongoose");

class Ingredient extends mongoose.model("Ingredient", new mongoose.Schema({
    ingredient: {
        type: String,
        required: true,
        unique: true
    }
}, {strict: "throw", versionKey: false})) {
    constructor(body) {
        if (body) {
            body.ingredient = body.ingredient.toLowerCase();
        }
        super(body);
    }
}

class Tag extends mongoose.model("Tag", new mongoose.Schema({
    tag: {
        type: String,
        required: true,
        unique: true
    }
}, {strict: "throw", versionKey: false})) {
    constructor(body) {
        if (body) {
            body.tag = body.tag.toLowerCase();
        }
        super(body);
    }
}

class Recipe extends mongoose.model("Recipe", new mongoose.Schema({
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
                        ref: "Ingredient",
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
    instructions: {
        type: [String],
        required: true
    },
    tags: {
        type: [
            {
                type: {
                    "tag": {
                        type: mongoose.Schema.Types.ObjectId,
                        ref: "Tag"
                    }
                },
                required: true,
                unique: true
            }
        ],
        options: {versionKey: false}
    }
}, {strict: "throw", versionKey: false})) {
    constructor(body) {
        if (body) {
            body.ingredients.forEach((ingredient) => {
                ingredient.ingredient = ingredient.ingredient.toLowerCase();
                ingredient.unit = ingredient.unit.toLowerCase();
            });
        }
        super(body);
    }
}

module.exports = {
    "ingredient": Ingredient,
    "tag": Tag,
    "recipe": Recipe
};