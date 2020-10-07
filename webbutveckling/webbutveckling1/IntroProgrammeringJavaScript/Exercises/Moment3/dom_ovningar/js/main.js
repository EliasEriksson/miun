"use strict";

let format = Intl.DateTimeFormat("sv", {
    year: "numeric", month: "numeric", day: "numeric", hour: "numeric", minute: "numeric", second: "numeric"
})

let date = document.createTextNode(format.format(new Date()));

document.getElementById("part1").appendChild(date);


// 2.2
let part2Button = document.getElementById("part2button");
let part2TextField = document.getElementById("part2");
part2Button.onclick = () => {
    alert(`Hej! ${part2TextField.value}`);
}

// 3.1
let part3 = document.getElementById("part3");
let part3Message = document.getElementById("part3message");
let part3Button = document.getElementById("part3button")
part3Button.disabled = true;
part3.addEventListener("keyup", ev => {
    let message = ""
    if (part3.value.length > 4) {
        part3Button.disabled = false;
        message = ""
    } else {
        part3Button.disabled = true;
        message = "användarnamnet är för kort"
    }
    part3Message.innerHTML = message;
})


// 3.2
part3Button.onclick = () => {
    console.log(`${part3.value} är inloggad.`);
    document.cookie = `username=${part3.value}`;
}


// 4.1
let part4 = document.getElementById("part4");
let part4Button = document.getElementById("part4button");
let part4Messages = document.getElementById("part4messages");

part4Button.onclick = () => {
    if (!part4.value > 0) return;
    let article = document.createElement("article");
    let text = document.createTextNode(part4.value);
    article.appendChild(text);

    article.addEventListener("click", () => {
        article.remove();
    })

    part4Messages.appendChild(article);
    part4.value = "";
}

// store message
let part5Store = document.getElementById("part5store");
part5Store.onclick = () => {
    let articles = document.evaluate("./article", part4Messages);
    let articlesText = [];
    for (let node; (node = articles.iterateNext());) {
        articlesText.push(node.textContent);
    }
    localStorage.setItem("articles", JSON.stringify(articlesText))
}

// load message
let part5Load = document.getElementById("part5load");
part5Load.onclick = () => {
    let articlesText = JSON.parse(localStorage.getItem("articles"));
    for (let i = 0; i < articlesText.length; i++) {
        let articleNode = document.createElement("article");
        let articleText = document.createTextNode(articlesText[i]);
        articleNode.appendChild(articleText);
        articleNode.addEventListener("click", () => {
            articleNode.remove();
        })
        part4Messages.appendChild(articleNode);
    }
}

window.addEventListener("load", () => {
    let cookies = document.cookie.split("; ");
    let cookie = "username="
    for (let i = 0; i < cookies.length; i++) {
        if (cookies[i].startsWith(cookie)) {
            console.log(`${cookies[i].slice(cookie.length)}`)
        }
    }
})