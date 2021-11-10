const express = require('express');
const fs = require("fs").promises;
const app = express();
const nunjucks = require("nunjucks");

const rootURL = "/jsweb/moment2";
const staticRoot = `${rootURL}/static`;
const apiRoot = `${rootURL}/api`;

app.use(express.json());

app.use(staticRoot, express.static(__dirname + "/static"));

const headers = {
    "json": {
        "content-type": "application/json"
    },
    "html": {
        "content-type": "text/html"
    }
};

const validateCourse = (course) => {
    if (!course["courseId"]) {
        throw new Error("Missing 'courseId' field.")
    } else if (!course["courseName"]) {
        throw new Error("Missing 'courseName' field.")
    } else if (!course["coursePeriod"]) {
        throw new Error("Missing 'coursePeriod' field.")
    } else if (Object.keys(course).length !== 3) {
        throw new Error("Too many fields.")
    }
};

const readDB = async () => {
    return JSON.parse(await fs.readFile("db.json", "utf-8"));
}

const writeDB = async (data) => {
    await fs.writeFile("db.json", JSON.stringify(data, null, 1), "utf-8");
}

const findCourse = (id, courses) => {
    for (const course of courses) {
        if (course.id === id) {
            return course;
        }
    }
    throw new Error("No such item.")
}

app.get(`${rootURL}/`, (request, response) => {
    let context = {
        staticRoot: staticRoot,
        rootURL: rootURL
    };
    let html = nunjucks.render(__dirname + "/templates/courses.njk", context);
    response.cookie("apiRoot", apiRoot);
    response.writeHead(200, {...headers.html});
    response.end(html);
});

app.get(`${rootURL}/course/:id`, async (request, response) => {
    try {
        let data = await readDB();
        let id = parseInt(request.params.id);
        let context = {
            staticRoot: staticRoot,
            rootURL: rootURL,
            course: findCourse(id, data)
        };
        let html = nunjucks.render(__dirname + "/templates/course.njk", context);
        response.cookie("apiRoot", apiRoot);
        response.writeHead(200, {...headers.html});
        response.end(html);
    } catch (e) {
        response.statusCode = 400;
        response.redirect("../");
    }
});

app.get(`${rootURL}/api/courses/`, async (request, response, next) => {
    try {
        let data = await readDB();
        response.writeHead(200, {...headers["json"]});
        response.end(JSON.stringify(data, null, 1));
    } catch (e) {
        next(e);
    }
});

app.get(`${rootURL}/api/course/:id`, async (request, response, next) => {
    try {
        let data = await readDB();

        if (data[request.params.id]) {
            response.writeHead(200, {...headers["json"]});
            response.end(JSON.stringify(data[request.params.id], null, 1));
        } else {
            throw new Error("No such item.");
        }
    } catch (e) {
        next(e);
    }
})

app.post(`${rootURL}/api/courses/`, async (request, response, next) => {
    try {
        validateCourse(request.body);
        let data = await readDB();

        let id = Math.max(...data.map(course => {
            return course.id;
        })) + 1;

        let course = {id: id, ...request.body};

        data.push(course);

        await writeDB(data);

        response.writeHead(201, {...headers["json"]});
        response.end(JSON.stringify(course));
    } catch (e) {
        response.statusCode = 400;
        next(e.message);
    }

});

app.put(`${rootURL}/api/course/:id`, async (request, response, next) => {
    try {
        validateCourse(request.body);
        let data = await readDB();
        let id = parseInt(request.params.id);

        let newCourse = null;
        for (const course of data) {
            if (course.id === id) {
                newCourse = {id: id, ...request.body};
                data[data.indexOf(course)] = newCourse;
            }
        }

        if (newCourse) {
            await writeDB(data);
            response.writeHead(200, {...headers.json});
            response.end(JSON.stringify(newCourse, null, 1));
        } else {
            throw new Error("No such item.");
        }
    } catch (e) {
        response.statusCode = 400;
        next(e);
    }
});

app.delete(`${rootURL}/course/:id`, async (request, response, next) => {
    try {
        let data = await readDB();
        let id = parseInt(request.params.id);

        let deleted = null;
        for (const course of data) {
            if (course.id === id) {
                deleted = data.splice(data.indexOf(course), 1)[0];
            }
        }

        if (deleted) {
            await writeDB(data);
            response.writeHead(200, {...headers.json});
            response.end(JSON.stringify(deleted, null, 1));
        } else {
            throw new Error("No such item.");
        }

    } catch (e) {
        response.statusCode = 400;
        next(e);
    }
});

const port = 8080;
app.listen(port, () => {
    console.log(`Example app listening at http://localhost:${port}${rootURL}`);
});
