const express = require('express');
const fs = require("fs").promises;
const app = express();
const nunjucks = require("nunjucks");

/**
 * the server will be behind an NGINX webserver that will proxy
 * URLs starting with /jsweb/moment2/ to this server
 * if this ever changes just change rootURL and everything will map properly
 */
const rootURL = "/jsweb/moment2";
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
    if (!course["courseId"]) {
        throw new Error("Missing 'courseId' field or field data.")
    } else if (!course["courseName"]) {
        throw new Error("Missing 'courseName' field or field data.")
    } else if (!course["coursePeriod"]) {
        throw new Error("Missing 'coursePeriod' field or field data.")
    } else if (Object.keys(course).length !== 3) {
        throw new Error("Too many fields.")
    }
};

/**
 * reads the "database" file to a list of javascript objects.
 *
 * @returns {Promise<Array<Object>>}
 */
const readDB = async () => {
    return JSON.parse(await fs.readFile("db.json", "utf-8"));
};

/**
 * writes a list of javascript objects to the "database file".
 *
 * @param data {Array<Object>}
 * @returns {Promise<void>}
 */
const writeDB = async (data) => {
    await fs.writeFile("db.json", JSON.stringify(data, null, 1), "utf-8");
};

/**
 * returns the course with given id from a list of courses
 *
 * @param id {number}
 * @param courses {Array<Object>}
 * @returns {Object}
 */
const findCourse = (id, courses) => {
    for (const course of courses) {
        if (course.id === id) {
            return course;
        }
    }
    throw new Error("No such item.");
};

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
app.get(`${rootURL}/course/`, async (request, response) => {
    response.redirect(rootURL);
});

/**
 * user endpoint for viewing a specific course in the "database" file.
 *
 * the endpoint allows the user to update or delete the course.
 * when the user updates or deletes a course they are redirected back to root.
 */
app.get(`${rootURL}/course/:id/`, async (request, response) => {
    try {
        let data = await readDB();
        let id = parseInt(request.params.id);
        let context = {
            staticRoot: staticRoot,
            rootURL: rootURL,
            course: findCourse(id, data)
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
        let data = await readDB();

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
app.get(`${apiRoot}/course/:id/`, async (request, response, next) => {
    try {
        let data = await readDB();

        // get course with given id
        let course = findCourse(parseInt(request.params.id), data);

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
        let data = await readDB();

        // generate an ID for the new course
        let id = Math.max(...data.map(course => {
            return course.id;
        })) + 1;

        // create the course
        let course = {id: id, ...request.body};

        // add the course
        data.push(course);

        // update the database
        await writeDB(data);

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
app.put(`${apiRoot}/course/:id/`, async (request, response, next) => {
    try {
        // validate the PUT data
        validateCourse(request.body);
        let data = await readDB();

        // get ID from URL
        let id = parseInt(request.params.id);

        // get current course with given id
        let course = findCourse(id, data);

        // create new course from old data and update with new data
        let newCourse = {...course, ...request.body};

        // insert the new object where the old one were
        data[data.indexOf(course)] = newCourse;

        // update the database
        await writeDB(data);
        // send status 200 and the data that the course was updated to use as JSON.
        response.writeHead(200, {...headers.json});
        response.end(JSON.stringify(newCourse, null, 1));

    } catch (e) {
        // if something goes wrong send back the error.
        response.statusCode = 400;
        next(e);
    }
});

/**
 * api endpoint for deleting an existing course from the "database" file.
 */
app.delete(`${apiRoot}/course/:id/`, async (request, response, next) => {
    try {
        let data = await readDB();
        // get ID from URL
        let id = parseInt(request.params.id);

        // get the course that is being deleted
        let course = findCourse(id, data);
        // remove the course
        data.splice(data.indexOf(course), 1);
        // update the database
        await writeDB(data);
        // send status 200 and the deleted course as JSON
        response.writeHead(200, {...headers.json});
        response.end(JSON.stringify(course, null, 1));

    } catch (e) {
        // if something goes wrong send back the error.
        response.statusCode = 400;
        next(e);
    }
});

const port = 8081;
app.listen(port, () => {
    console.log(`Example app listening at http://localhost:${port}${rootURL}`);
});
