const express = require("express");
const fs = require("fs").promises;
const mongoose = require("mongoose");
const models = require("./modules/models");

const rootURL = "/jsweb/moment5";
const apiRoot = `${rootURL}/api`;

const app = express();
app.use(express.json());
app.use(rootURL, express.static(`${__dirname}/static`));

app.get(`${apiRoot}/ingredients/`, async (request, response, next) => {
    try {
        await response.status(200).json(await models.ingredient.find());
        next();
    } catch (e) {
        next(e);
    }
});

app.get(`${apiRoot}/ingredients/:id/`, async (request, response, next) => {
    try {
        const ingredient = await models.ingredient.findById(request.params.id);
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
        response.status(201).json(ingredient);
        next();
    } catch (e) {
        next(e);
    }
});

app.put(`${apiRoot}/ingredients/:id`, async (request, response, next) => {
    try {
        const ingredient = await models.ingredient.findByIdAndUpdate(request.params.id, request.body);
        response.status(200).json(ingredient);
        next();
    } catch (e) {
        next(e);
    }
});

app.delete(`${apiRoot}/ingredients/:id`, async (request, response, next) => {
    try {
        const ingredient = await models.ingredient.findByIdAndDelete(request.params.id);
        response.status(200).json(ingredient);
        next();
    } catch (e) {
        next(e);
    }
});

app.get(`${apiRoot}/tags/`, async (request, response, next) => {
    try {
        await response.status(200).json(await models.tag.find());
        next();
    } catch (e) {
        next(e);
    }
});

app.get(`${apiRoot}/tags/:id`, async (request, response, next) => {
    try {
        const tag = await models.tag.findById(request.params.id);
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
        response.status(201).json(tag);
        next();
    } catch (e) {
        next(e);
    }
});

app.put(`${apiRoot}/tags/:id`, async (request, response, next) => {
    try {
        const tag = await models.tag.findByIdAndUpdate(request.params.id, request.body);
        response.status(200).json(tag);
        next();
    } catch (e) {
        next(e);
    }
});

app.delete(`${apiRoot}/tags/:id`, async (request, response, next) => {
    try {
        const tag = await models.tag.findByIdAndDelete(request.params.id);
        response.status(200).json(tag);
        next();
    } catch (e) {
        next(e);
    }
});

app.get(`${apiRoot}/recipes/`, async (request, response, next) => {
    try {
        response.status(200).json(
            await models.recipe.find().populate("ingredients.ingredient").populate("tags.tag")
        );
        next();
    } catch (e) {
        next(e);
    }
});

app.get(`${apiRoot}/recipes/:id`, async (request, response, next) => {
    try {
        const recipe = await models.recipe.findById(request.params.id).populate("ingredients.ingredient");
        await response.status(200).json(recipe);
    } catch (e) {
        next(e);
    }
});

app.post(`${apiRoot}/recipes/`, async (request, response, next) => {
    try {
        const recipe = new models.recipe(request.body);
        await recipe.save();
        response.status(201).json(recipe);
        next();
    } catch (e) {
        next(e);
    }
});

app.put(`${apiRoot}/recipes/:id`, async (request, response, next) => {
    try {
        const recipe = await models.recipe.findByIdAndUpdate(request.params.id, request.body);
        response.status(200).json(recipe);
        next()
    } catch (e) {
        next(e);
    }
});

app.delete(`${apiRoot}/recipes/:id`, async (request, response, next) => {
    console.log("was here")
    try {
        const recipe = await models.recipe.findByIdAndDelete(request.params.id)
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


