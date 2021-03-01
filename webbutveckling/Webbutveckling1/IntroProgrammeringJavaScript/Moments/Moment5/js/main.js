let output = document.getElementById("info");
let sites = document.getElementById("sites");


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

function addName(name, element) {
    addStrong("Name", element);

    let text = document.createTextNode(name);
    element.appendChild(text);

    addBr(element);
}

function addEmail(email, element) {
    addStrong("Email", element);

    let a = document.createElement("a");
    a.setAttribute("href", `mailto:${email}`);
    a.innerHTML = email;
    element.appendChild(a);

    addBr(element);
}

function addLinkAsElement(link, element) {
    addStrong("Website", element, false);

    let a = document.createElement("a");
    a.setAttribute("href", link);
    a.setAttribute("target", "_blank");
    a.innerHTML = link;
    addBr(a, element);

    element.appendChild(a);
}

function handleStudentInformation(information) {
    if (information.hasOwnProperty("name")){
        addName(information.name, output)
    }
    if (information.hasOwnProperty("email")){
        addEmail(information.email, output);
    }
    if (information.hasOwnProperty("website")){
        addLinkAsElement(information.website, output);
    }
}

function handleWebsite(website, list) {
    let item = document.createElement("li");
    let link = document.createElement("a");
    item.appendChild(link);

    if (website.hasOwnProperty("sitename")){
        link.textContent = website.sitename;
    }
    if (website.hasOwnProperty("siteurl")){
        link.setAttribute("href", website.siteurl);
    }
    if (website.hasOwnProperty("description")){
        link.setAttribute("target", "_blank");
    }
    list.appendChild(item);
}

function handleStudentWebsites(websites) {
    for (let i in websites){
        if (websites.hasOwnProperty(i)){
            handleWebsite(websites[i], sites);
        }
    }
}

let request = new XMLHttpRequest();
request.addEventListener("readystatechange", function () {
    if (this.readyState !== 4) return;
    if (this.status !== 200) return;

    let json = JSON.parse(this.response);
    if (json.hasOwnProperty("student")) {
        let student = json.student;
        if (student.hasOwnProperty("information")) {
            handleStudentInformation(student.information);
        }
        if (student.hasOwnProperty("websites")) {
            handleStudentWebsites(student.websites);
        }
    }
})

request.open("get", "student.json", true);
request.send();
