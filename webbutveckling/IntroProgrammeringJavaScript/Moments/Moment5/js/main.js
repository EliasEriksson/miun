let info = document.getElementById("info");

function addBr(element){
    let br = document.createElement("br");
    element.appendChild(br);
}

function addStrong(content, element, colonInside=true) {
    let strong = document.createElement("strong");
    strong.innerHTML = content + (colonInside ? ":" : "");
    if (colonInside) {
        strong.innerHTML = content + ":";
        element.appendChild(strong);
    } else {
        strong.innerHTML = content;
        element.appendChild(strong);
        element.innerHTML += ":";
    }

}

function addTextAsElement(content, element) {
    let text = document.createTextNode(content);
    element.appendChild(text);
}

function addEmailAsElement(email, element) {
    let a = document.createElement("a");
    a.setAttribute("href", `mailto:${email}`);
    a.innerHTML = email;
    element.appendChild(a);
}

function addLinkAsElement(link, element, br=false) {
    let a = document.createElement("a");
    a.setAttribute("href", link)
    a.innerHTML = `${link}${br ? "<br>" : ""}`
    element.appendChild(a);
}

function handleStudentInformation(information) {
    for (let key in information){
        if (information.hasOwnProperty(key)) {
            switch (key) {
                case "name":
                    addStrong("Namn", info);
                    addTextAsElement(information[key], info);
                    addBr(info);
                    break;
                case "email":
                    addStrong("E-post", info);
                    addEmailAsElement(information[key], info);
                    addBr(info);
                    break;
                case "website":
                    addStrong("Webbplats", info, false);
                    addLinkAsElement(information[key], info, true);
                    break;
            }
        }
    }
}

function handleWebsites(websites) {
    for (let i in websites){
        if (websites.hasOwnProperty(i)){
            handleWebsite(websites[i]);
        }
    }
}

function handleWebsite(website) {
    for (let key in website) {
        if (website.hasOwnProperty(key)) {
            switch (key) {
                case "sitename":
                    break;
                case "siteurl":
                    break;
                case "description":
                    break;
            }
        }
    }
}


let request = new XMLHttpRequest();
request.addEventListener("readystatechange", function () {
    if (this.readyState !== 4) return;
    let json = JSON.parse(this.response);
    if (json.hasOwnProperty("student")) {
        let student = json["student"];
        for (let key in student) {
            if (student.hasOwnProperty(key)) {
                switch (key) {
                    case "information":
                        handleStudentInformation(student[key]);
                        break;
                    case "websites":
                        handleWebsites(student[key])
                        break;
                }
            }
        }
    }
})

request.open("get", "student.json", true);
request.send();
