const fs = require("fs").promises;

const main = async ()=> {
    const content = JSON.parse(await fs.readFile("db.json", "utf-8"));
    console.log(content)
}

main();