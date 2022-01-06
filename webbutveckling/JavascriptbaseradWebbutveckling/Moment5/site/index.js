const mongoose = require("mongoose");
mongoose.plugin(require("mongoose-aggregate-paginate-v2"));
mongoose.plugin(require("mongoose-paginate-v2"));
const express = require("express");
const fs = require("fs").promises;
const models = require("./modules/models");


const rootURL = "/jsweb/moment5";
const apiRoot = `${rootURL}/api`;

const app = express();
app.use(express.json());
app.use(rootURL, express.static(`${__dirname}/static`));


const paginateParams = (request) => {
    return {
        page: request.query?.page ? parseInt(request.query.page) : 1,
        limit: request.query?.limit ? parseInt(request.query.limit) : 5
    };
}

app.get(`${apiRoot}/ingredients/`, async (request, response, next) => {
    try {
        let pattern = `${request.query.s ?? ""}`;
        if (request.query.exact === "true") {

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

app.get(`${apiRoot}/ingredients/:id/`, async (request, response, next) => {
    try {
        const ingredient = await models.Ingredients.findById(request.params.id);

        await response.status(200).json(ingredient);
        next();
    } catch (e) {
        next(e);
    }
});

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

app.get(`${apiRoot}/tags/:id`, async (request, response, next) => {
    try {
        const tag = await models.Tags.findById(request.params.id);

        await response.status(200).json(tag);
        next();
    } catch (e) {
        next(e);
    }
});

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

app.get(`${apiRoot}/recipes/`, async (request, response, next) => {
    try {
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

app.get(`${apiRoot}/recipes/:id`, async (request, response, next) => {
    try {
        const recipe = await models.Recipes.findById(request.params.id).populate("ingredients.ingredient").populate("tags.tag");

        await response.status(200).json(recipe);
    } catch (e) {
        next(e);
    }
});

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

const readCredentials = async () => {
    return await JSON.parse(await fs.readFile(".credentials.json", "utf-8"));
}

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


