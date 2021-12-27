const mongoose = require("mongoose");
const mongoosePaginate = require("mongoose-paginate-v2");

const ingredientSchema = new mongoose.Schema({
    ingredient: {
        type: String,
        required: true,
        unique: true
    }
}, {strict: "throw", versionKey: false});
ingredientSchema.plugin(mongoosePaginate);

const tagSchema = new mongoose.Schema({
    tag: {
        type: String,
        required: true,
        unique: true
    }
}, {strict: "throw", versionKey: false});
tagSchema.plugin(mongoosePaginate);

const recipeSchema = new mongoose.Schema({
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
}, {strict: "throw", versionKey: false});
recipeSchema.plugin(mongoosePaginate);

class Ingredient extends mongoose.model("Ingredient", ingredientSchema) {
    constructor(body) {
        if (body) {
            body.ingredient = body.ingredient.toLowerCase();
        }
        super(body);
    }
}

class Tag extends mongoose.model("Tag", tagSchema) {
    constructor(body) {
        if (body) {
            body.tag = body.tag.toLowerCase();
        }
        super(body);
    }
}

class Recipe extends mongoose.model("Recipe", recipeSchema) {
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