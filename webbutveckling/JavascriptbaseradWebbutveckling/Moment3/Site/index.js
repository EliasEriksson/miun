const express = require('express');
const fs = require("fs").promises;
const app = express();
const nunjucks = require("nunjucks");
const mongoose = require("mongoose");
const models = require("./modules/models");

/**
 * the server will be behind an NGINX webserver that will proxy
 * URLs starting with /jsweb/moment2/ to this server
 * if this ever changes just change rootURL and everything will map properly
 */
const rootURL = "/jsweb/moment3";
const staticRoot = `${rootURL}/static`;
const apiRoot = `${rootURL}/api`;


/**
 * parses body content to json if Content-Type header from client
 * is set to application/json
 */
app.use(express.json());

/**
 * maps requests for static files (css / js / media / html) to the static directory
 */
app.use(staticRoot, express.static(__dirname + "/static"));

const headers = {
    "json": {
        "content-type": "application/json"
    },
    "html": {
        "content-type": "text/html"
    }
};

/**
 * makes sure that the course have the correct fields.
 *
 * the id attribute is not checked in this function as no POST / PUT data
 * is expected to contain the ID since the ID is in the URL.
 * @param course
 */
const validateCourse = (course) => {
    if (!course["code"]) {
        throw new Error("Missing 'code' field or field data.")
    } else if (!course["name"]) {
        throw new Error("Missing 'name' field or field data.")
    } else if (!course["period"]) {
        throw new Error("Missing 'period' field or field data.")
    } else if (Object.keys(course).length !== 3) {
        throw new Error("Too many fields.")
    }
};

/**
 *
 * @returns {Promise<{user: string, pwd: string, db: string, host: string, port: number}>}
 */
const readCredentials = async () => {
    return await JSON.parse(await fs.readFile(".credentials.json", "utf-8"));
}

/**
 * write the URLs to cookies so content can be consumed
 * with fetch in client side JS.
 *
 * @param response
 */
const writeCookies = (response) => {
    response.cookie("rootURL", rootURL);
    response.cookie("apiRoot", apiRoot);
    response.cookie("staticRoot", staticRoot);
};

/**
 * user endpoint for viewing all the content in the "database" file.
 */
app.get(`${rootURL}/`, (request, response) => {
    // variables for the templating engine nunjucks
    let context = {
        staticRoot: staticRoot,
        rootURL: rootURL
    };

    // render the page to HTML
    let html = nunjucks.render(__dirname + "/templates/courses.njk", context);
    writeCookies(response);

    // send off the request with status 200 and HTML content header
    response.writeHead(200, {...headers.html});
    response.end(html);
});

/**
 * there is no content to display on this endpoint.
 *
 * if they somehow end up here they are redirected back to the sites root.
 */
app.get(`${rootURL}/courses/`, async (request, response) => {
    response.redirect(rootURL);
});

/**
 * user endpoint for viewing a specific course in the "database" file.
 *
 * the endpoint allows the user to update or delete the course.
 * when the user updates or deletes a course they are redirected back to root.
 */
app.get(`${rootURL}/courses/:id/`, async (request, response) => {
    try {
        let context = {
            staticRoot: staticRoot,
            rootURL: rootURL,
            course: await models.Course.findById(request.params.id)
        };
        let html = nunjucks.render(__dirname + "/templates/course.njk", context);
        writeCookies(response);
        response.writeHead(200, {...headers.html});
        response.end(html);
    } catch (e) {
        // if something goes wrong redirect to root
        response.redirect(rootURL);
    }
});

/**
 * api endpoint for retrieving all courses in the "database" file.
 */
app.get(`${apiRoot}/courses/`, async (request, response, next) => {
    try {
        let data = await models.Course.find();

        // send back status 200 and all courses as JSON
        response.writeHead(200, {...headers["json"]});
        response.end(JSON.stringify(data, null, 1));
    } catch (e) {
        // if something goes wrong send back the error
        response.statusCode = 400;
        next(e);
    }
});

/**
 * api endpoint for retrieving a single course from the "database" file.
 */
app.get(`${apiRoot}/courses/:id/`, async (request, response, next) => {
    try {
        let course = await models.Course.findById(request.params.id);

        // get course with given id
        // let course = findCourse(parseInt(request.params.id), data);

        // send back status 200 and the requested course as JSON
        response.writeHead(200, {...headers["json"]});
        response.end(JSON.stringify(course, null, 1));
    } catch (e) {
        // if something goes wrong send back the error
        response.statusCode = 400;
        next(e);
    }
})

/**
 * api endpoint for creating a new course and adding it to the "database" file.
 */
app.post(`${apiRoot}/courses/`, async (request, response, next) => {
    try {
        // validate the posted data
        validateCourse(request.body);

        let course = new models.Course(request.body);
        await course.save();

        // send status 201 and the created course as JSON
        response.writeHead(201, {...headers["json"]});
        response.end(JSON.stringify(course));
    } catch (e) {
        // if something goes wrong send back the error.
        response.statusCode = 400;
        next(e.message);
    }

});

/**
 * api endpoint for updating an already existing course in the "database" file.
 */
app.put(`${apiRoot}/courses/:id/`, async (request, response, next) => {
    try {
        // validate the PUT data
        validateCourse(request.body);

        const course = await models.Course.findByIdAndUpdate(request.params.id, request.body);

        response.writeHead(200, {...headers.json});
        response.end(JSON.stringify(course, null, 1));

    } catch (e) {
        // if something goes wrong send back the error.
        response.statusCode = 400;
        next(e);
    }
});

/**
 * api endpoint for deleting an existing course from the "database" file.
 */                     // /course/
app.delete(`${apiRoot}/courses/:id/`, async (request, response, next) => {
    try {
        const course = await models.Course.findByIdAndRemove(request.params.id);

        // send status 200 and the deleted course as JSON
        response.writeHead(200, {...headers.json});
        response.end(JSON.stringify(course, null, 1));

    } catch (e) {
        // if something goes wrong send back the error.
        response.statusCode = 400;
        next(e);
    }
});

const webPort = 8082;
readCredentials().then(async cred => {
    await mongoose.connect(
        `mongodb://${cred.user}:${cred.pwd}@${cred.host}:${cred.port}/${cred.db}`
    );
    app.listen(webPort, async () => {
        console.log(`Example app listening at http://localhost:${webPort}${rootURL}`);
    });
})