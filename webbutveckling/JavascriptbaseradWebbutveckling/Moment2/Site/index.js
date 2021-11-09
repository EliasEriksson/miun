const express = require('express');
const fs = require("fs").promises;
const app = express();
const port = 8080;

const readDB = async () => {
    return JSON.parse(await fs.readFile("db.json", "utf-8"));
}

const writeDB = async (data) => {
    await fs.writeFile("db.json", JSON.stringify(data), "utf-8");
}

// app.get('/', (request, response) => {
//     response.send('Hello World!');
// });

app.get("/", async (request, response, next) => {
    try {
        let data = await readDB();
        headers = {
            "Content-Type": "application/json"
        }
        response.writeHead()
        await response.send(JSON.stringify(data));
    } catch (e) {
        console.log(e);
        next(e);
    }

});

app.listen(port, () => {
    console.log(`Example app listening at http://localhost:${port}`)
});

