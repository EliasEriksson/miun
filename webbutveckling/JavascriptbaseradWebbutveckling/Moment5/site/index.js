const mongoose = require("mongoose");
mongoose.plugin(require("mongoose-paginate-v2"));
const express = require("express");
const fs = require("fs").promises;
const models = require("./modules/models");

const rootURL = "/jsweb/moment5";
const apiRoot = `${rootURL}/api`;

const app = express();
app.use(express.json());
app.use(rootURL, express.static(`${__dirname}/static`));

const writeCookies = (response) => {
    response.cookie("rootURL", rootURL);
    response.cookie("apiRoot", apiRoot);
}

const searchParams = (request) => {
    const defaultOptions = {
        page: 1,
        limit: 5
    }
    if (request.query.page) {
        defaultOptions.page = parseInt(request.query.page);
    }

    if (request.query.limit) {
        defaultOptions.limit = parseInt(request.query.limit);
    }
    return defaultOptions;
}

app.get(`${apiRoot}/ingredients/`, async (request, response, next) => {
    try {
        writeCookies(response);
        await response.status(200).json(await models.ingredient.paginate({}, {
            ...searchParams(request)
        }));
        next();
    } catch (e) {
        next(e);
    }
});

app.get(`${apiRoot}/ingredients/:id/`, async (request, response, next) => {
    try {
        const ingredient = await models.ingredient.findById(request.params.id);
        writeCookies(response);
        await response.status(200).json(ingredient);
        next();
    } catch (e) {
        next(e);
    }
});

app.post(`${apiRoot}/ingredients/`, async (request, response, next) => {
    try {
        const ingredient = new models.ingredient(request.body);
        await ingredient.save();
        writeCookies(response);
        response.status(201).json(ingredient);
        next();
    } catch (e) {
        next(e);
    }
});

app.put(`${apiRoot}/ingredients/:id`, async (request, response, next) => {
    try {
        const ingredient = await models.ingredient.findByIdAndUpdate(request.params.id, request.body);
        writeCookies(response);
        response.status(200).json(ingredient);
        next();
    } catch (e) {
        next(e);
    }
});

app.delete(`${apiRoot}/ingredients/:id`, async (request, response, next) => {
    try {
        const ingredient = await models.ingredient.findByIdAndDelete(request.params.id);
        writeCookies(response);
        response.status(200).json(ingredient);
        next();
    } catch (e) {
        next(e);
    }
});

app.get(`${apiRoot}/tags/`, async (request, response, next) => {
    try {
        writeCookies(response);
        await response.status(200).json(await models.tag.paginate({}, {
            ...searchParams(request)
        }));
        next();
    } catch (e) {
        next(e);
    }
});

app.get(`${apiRoot}/tags/:id`, async (request, response, next) => {
    try {
        const tag = await models.tag.findById(request.params.id);
        writeCookies(response);
        await response.status(200).json(tag);
        next();
    } catch (e) {
        next(e);
    }
});

app.post(`${apiRoot}/tags/`, async (request, response, next) => {
    try {
        const tag = new models.tag(request.body);
        await tag.save();
        writeCookies(response);
        response.status(201).json(tag);
        next();
    } catch (e) {
        next(e);
    }
});

app.put(`${apiRoot}/tags/:id`, async (request, response, next) => {
    try {
        const tag = await models.tag.findByIdAndUpdate(request.params.id, request.body);
        writeCookies(response);
        response.status(200).json(tag);
        next();
    } catch (e) {
        next(e);
    }
});

app.delete(`${apiRoot}/tags/:id`, async (request, response, next) => {
    try {
        const tag = await models.tag.findByIdAndDelete(request.params.id);
        writeCookies(response);
        response.status(200).json(tag);
        next();
    } catch (e) {
        next(e);
    }
});

app.get(`${apiRoot}/recipes/`, async (request, response, next) => {
    try {
        writeCookies(response);
        response.status(200).json(
            await models.recipe.paginate({}, {
                ...searchParams(request),
                populate: [
                    "ingredients.ingredient",
                    "tags.tag"
                ]
            })
        );
        next();
    } catch (e) {
        next(e);
    }
});

app.get(`${apiRoot}/recipes/:id`, async (request, response, next) => {
    try {
        const recipe = await models.recipe.findById(request.params.id).populate("ingredients.ingredient");
        writeCookies(response);
        await response.status(200).json(recipe);
    } catch (e) {
        next(e);
    }
});

app.post(`${apiRoot}/recipes/`, async (request, response, next) => {
    try {
        const recipe = new models.recipe(request.body);
        await recipe.save();
        writeCookies(response);
        response.status(201).json(recipe);
        next();
    } catch (e) {
        next(e);
    }
});

app.put(`${apiRoot}/recipes/:id`, async (request, response, next) => {
    try {
        const recipe = await models.recipe.findByIdAndUpdate(request.params.id, request.body);
        writeCookies(response);
        response.status(200).json(recipe);
        next()
    } catch (e) {
        next(e);
    }
});

app.delete(`${apiRoot}/recipes/:id`, async (request, response, next) => {
    try {
        const recipe = await models.recipe.findByIdAndDelete(request.params.id)
        writeCookies(response);
        response.status(200).json(recipe);
        next();
    } catch (e) {
        next(e);
    }
});

const readCredentials = async () => {
    return await JSON.parse(await fs.readFile(".credentials.json", "utf-8"));
}

app.get(`${apiRoot}`, async (request, response, next) => {
    try {
        writeCookies(response);
        await response.json({"message": "hello world"});
    } catch (e) {
        next(e);
    }
});

const PORT = 8083;
readCredentials().then(async cred => {
    const mongoConnect = `mongodb://${cred.user}:${cred.pwd}@${cred.host}:${cred.port}/${cred.db}`;
    console.log(`Connecting to mongo db...`)
    await mongoose.connect(mongoConnect);
    console.log("Connected to mongodb!")

    app.listen(PORT, () => {
        console.log(`Server is running on http://localhost:${PORT}${rootURL}`);
    });
});


