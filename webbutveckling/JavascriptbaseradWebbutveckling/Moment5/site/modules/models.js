const mongoose = require("mongoose");

// const Ingredient = mongoose.model("Ingredient", new mongoose.Schema({
//     ingredient: {
//         type: [String],
//         required: true,
//         unique: true
//     }
// }, {strict: "throw"}));
//
// const Recipe = mongoose.model("Recipe", new mongoose.Schema({
//     title: {
//         type: String,
//         required: true
//     },
//     description: {
//         type: String,
//         required: true
//     },
//     ingredients: {
//         type: [
//             {
//                 type: {
//                     "ingredient": {
//                         type: mongoose.Schema.Types.ObjectId,
//                         ref: "Ingredient"
//                     },
//                     "amount": Number
//                 },
//                 required: true
//             }
//         ],
//         required: true
//     },
//     instructions: {
//         type: [String],
//         required: true
//     }
// }, {strict: "throw"}));

class Ingredient extends mongoose.model("Ingredient", new mongoose.Schema({
    ingredient: {
        type: String,
        required: true,
        unique: true
    }
}, {strict: "throw"})) {
    // save = async (callback) => {
    //     await super.save();
    //     if (callback) {
    //         await callback();
    //     }
    // }
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
                        ref: "Ingredient"
                    },
                    "amount": Number
                },
                required: true
            }
        ],
        required: true
    },
    instructions: {
        type: [String],
        required: true
    }
}, {strict: "throw"})) {
    // save = async (callback) => {
    //     await super.save();
    //
    //     if (callback) {
    //         await callback();
    //     }
    // }
}

module.exports = {
    "ingredient": Ingredient,
    "recipe": Recipe
};