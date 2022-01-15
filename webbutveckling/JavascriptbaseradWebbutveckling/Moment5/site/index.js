const mongoose = require("mongoose");
mongoose.plugin(require("mongoose-paginate-v2"));
const express = require("express");
const fs = require("fs").promises;
const models = require("./modules/models");


const rootURL = "/jsweb/moment5";
const apiRoot = `${rootURL}/api`;

const app = express();
app.use(express.json());

/**
 * reads the query params limit and page from the request
 *
 * the params are returned as an object
 * they have default values if the query params are not
 * sent with the request
 *
 * @param request
 * @returns {{limit: (number), page: (number)}}
 */
const paginateParams = (request) => {
    return {
        page: request.query?.page ? parseInt(request.query.page) : 1,
        limit: request.query?.limit ? parseInt(request.query.limit) : 5
    };
}

/**
 * get request for a list of ingredients
 *
 * query params:
 *  * limit: int, the amount of ingredients returned
 *  * page: int, the page number of ingredients
 *  * s: string, a search string
 *  * exact: bool, modifies the search to be an exact match
 */
app.get(`${apiRoot}/ingredients/`, async (request, response, next) => {
    try {
        let pattern = `${request.query.s ?? ""}`;
        if (request.query.exact === "true") {
            pattern = "^" + pattern + "$"
        }
        const search = new RegExp(pattern);
        await response.status(200).json(await models.Ingredients.paginate({ingredient: search}, {
            ...paginateParams(request)
        }));
        next();
    } catch (e) {
        next(e);
    }
});

/**
 * get request for a single ingredient with a specific id
 */
app.get(`${apiRoot}/ingredients/:id/`, async (request, response, next) => {
    try {
        const ingredient = await models.Ingredients.findById(request.params.id);

        await response.status(200).json(ingredient);
        next();
    } catch (e) {
        next(e);
    }
});

/**
 * creates a new ingredient
 *
 * json format:
 * {
 *     "ingredient": string
 * }
 */
app.post(`${apiRoot}/ingredients/`, async (request, response, next) => {
    try {
        const ingredient = new models.Ingredients(request.body);
        await ingredient.save();

        response.status(201).json(ingredient);
        next();
    } catch (e) {
        try {
            response.status(400).send(e.errors);
            next();
        } catch (e) {
            next(e);
        }
    }
});

/**
 * updates the data of an ingredient with the specified id
 *
 * json format:
 * {
 *     "ingredient": string
 * }
 */
app.put(`${apiRoot}/ingredients/:id`, async (request, response, next) => {
    try {
        const ingredient = await models.Ingredients.findByIdAndUpdate(request.params.id, request.body);

        response.status(200).json(ingredient);
        next();
    } catch (e) {
        try {
            response.status(400).send(e.errors);
            next();
        } catch (e) {
            next(e);
        }
    }
});

/**
 * deletes the ingredient with the specified id
 */
app.delete(`${apiRoot}/ingredients/:id`, async (request, response, next) => {
    try {
        const ingredient = await models.Ingredients.findByIdAndDelete(request.params.id);

        response.status(200).json(ingredient);
        next();
    } catch (e) {
        try {
            response.status(400).send(e.errors);
            next();
        } catch (e) {
            next(e);
        }
    }
});

/**
 * get request for a list of tags
 *
 * query params:
 *  * limit: int, the amount of tags returned
 *  * page: int, the page number of tags
 *  * s: string, a search string
 *  * exact: bool, modifies the search to be an exact match
 */
app.get(`${apiRoot}/tags/`, async (request, response, next) => {
    try {
        let pattern = `${request.query.s ?? ""}`;
        if (request.query.exact === "true") {
            pattern = "^" + pattern + "$"
        }
        const search = new RegExp(pattern);
        await response.status(200).json(await models.Tags.paginate({tag: search}, {
            ...paginateParams(request)
        }));
        next();
    } catch (e) {
        next(e);
    }
});

/**
 * get request for a single tag with a specific id
 */
app.get(`${apiRoot}/tags/:id`, async (request, response, next) => {
    try {
        const tag = await models.Tags.findById(request.params.id);

        await response.status(200).json(tag);
        next();
    } catch (e) {
        next(e);
    }
});

/**
 * creates a new tag
 *
 * json format:
 * {
 *     "tag": string
 * }
 */
app.post(`${apiRoot}/tags/`, async (request, response, next) => {
    try {
        const tag = new models.Tags(request.body);
        await tag.save();
        response.status(201).json(tag);
        next();
    } catch (e) {
        try {
            response.status(400).send(e.errors);
            next();
        } catch (e) {
            next(e);
        }
    }
});

/**
 * updates
 *
 * json format:
 * {
 *     "tag": string
 * }
 */
app.put(`${apiRoot}/tags/:id`, async (request, response, next) => {
    try {
        const tag = await models.Tags.findByIdAndUpdate(request.params.id, request.body, {

        });
        response.status(200).json(tag);
        next();
    } catch (e) {
        try {
            response.status(400).send(e.errors);
            next();
        } catch (e) {
            next(e);
        }
    }
});

/**
 * deletes a tag with the specified id
 */
app.delete(`${apiRoot}/tags/:id`, async (request, response, next) => {
    try {
        const tag = await models.Tags.findByIdAndDelete(request.params.id);

        response.status(200).json(tag);
        next();
    } catch (e) {
        try {
            response.status(400).send(e.errors);
            next();
        } catch (e) {
            next(e);
        }
    }
});

/**
 * get request for a list of recipes
 *
 * query params:
 *  * limit: int, the amount of ingredients returned
 *  * page: int, the page number of ingredients
 *  * s: string, a search string
 */
app.get(`${apiRoot}/recipes/`, async (request, response, next) => {
    try {
        // creating options here where paginateParams is in scope.
        const options = {
            ...paginateParams(request),
            populate: [
                "ingredients.ingredient",
                "tags.tag"
            ]
        }
        if (request.query.s) {
            response.status(200).json(
                await models.Recipes.search(request.query.s, options)
            );
        } else {
            response.status(200).json(
                await models.Recipes.paginate({}, options)
            );
        }

        next();
    } catch (e) {
        next(e);
    }
});

/**
 * get request for a single recipe with a specific id
 */
app.get(`${apiRoot}/recipes/:id`, async (request, response, next) => {
    try {
        const recipe = await models.Recipes.findById(request.params.id).populate("ingredients.ingredient").populate("tags.tag");

        await response.status(200).json(recipe);
    } catch (e) {
        next(e);
    }
});

/**
 * creates a new recipe
 *
 * the "tag" string is a mongodb id.
 * the "ingredient" string is a mongodb id.
 *
 * json format:
 * {
 *     "title: string,
 *     "description": string,
 *     "ingredients":
 *     {
 *         "ingredient": string,
 *         "amount": float,
 *         "unit": "st"|"l"|"dl"|"cl"|"ml"|"kg"|"g"
 *     }[],
 *     "instructions":
 *     {
 *         "instruction": string
 *     }[],
 *     "tags":
 *     {
 *         "tag": string
 *     }[]
 * }
 */
app.post(`${apiRoot}/recipes/`, async (request, response, next) => {
    try {
        const recipe = new models.Recipes(request.body);
        await recipe.save();

        response.status(201).json(
            await models.Recipes.findById(recipe._id).populate("ingredients.ingredient").populate("tags.tag")
        );
        next();
    } catch (e) {
        try {
            response.status(400).json(e.errors);
            next();
        } catch (e) {
            next(e);
        }
    }
});

/**
 * updates the date of the specified recipe
 *
 * the "tag" string is a mongodb id.
 * the "ingredient" string is a mongodb id.
 *
 * json format:
 * {
 *     "title: string,
 *     "description": string,
 *     "ingredients":
 *     {
 *         "ingredient": string,
 *         "amount": float,
 *         "unit": "st"|"l"|"dl"|"cl"|"ml"|"kg"|"g"
 *     }[],
 *     "instructions":
 *     {
 *         "instruction": string
 *     }[],
 *     "tags":
 *     {
 *         "tag": string
 *     }[]
 * }
 */
app.put(`${apiRoot}/recipes/:id`, async (request, response, next) => {
    try {
        const recipe = await models.Recipes.findAndUpdate(request.params.id, request.body);

        response.status(200).json(
            await models.Recipes.findById(recipe.id).populate("ingredients.ingredient").populate("tags.tag")
        );
        next();
    } catch (e) {
        try {
            response.status(400).send(e.errors);
            next();
        } catch (e) {
            next(e);
        }
    }
});

/**
 * deletes the recipe with the specified id
 */
app.delete(`${apiRoot}/recipes/:id`, async (request, response, next) => {
    try {
        const recipe = await models.Recipes.findByIdAndDelete(request.params.id)
        response.status(200).json(recipe);
        next();
    } catch (e) {
        try {
            response.status(400).send(e.errors);
            next();
        } catch (e) {
            next(e);
        }
    }
});

/**
 * reads the credentials file for db credentials.
 *
 * @returns {Promise<any>}
 */
const readCredentials = async () => {
    return await JSON.parse(await fs.readFile(".credentials.json", "utf-8"));
}

/**
 * starts the express server with a mongodb connection.
 */
const PORT = 8083;
readCredentials().then(async cred => {
    const mongoConnect = `mongodb://${cred.user}:${cred.pwd}@${cred.host}:${cred.port}/${cred.db}`;
    console.log(`Connecting to mongo db...`);
    await mongoose.connect(mongoConnect);
    console.log("Connected to mongodb!");

    app.listen(PORT, () => {
        console.log(`Server is running on http://localhost:${PORT}${rootURL}`);
    });
});


