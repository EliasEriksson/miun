let output = document.getElementById("info");
let sites = document.getElementById("sites");


function addBr(element){
    /**
     * adds br tag to element
     */
    let br = document.createElement("br");
    element.appendChild(br);
}

function addStrong(content, element, colonInside=true) {
    /**
     * adds content in a strong tag to element
     *
     *
     * option for colon inside or outside of the strong tag
     */
    let strong = document.createElement("strong");
    if (colonInside) {
        strong.innerHTML = content + ": ";
        element.appendChild(strong);
    } else {
        strong.innerHTML = content;
        element.appendChild(strong);
        element.innerHTML += ": ";
    }

}

function addName(name, element) {
    /**
     * adds a strong tag, name & br tag to element
     */
    addStrong("Name", element);

    let text = document.createTextNode(name);
    element.appendChild(text);

    addBr(element);
}

function addEmail(email, element) {
    /**
     * adds email: in strong tag, an email link and a br tag to element
     */
    addStrong("Email", element);

    let a = document.createElement("a");
    a.setAttribute("href", `mailto:${email}`);
    a.innerHTML = email;
    element.appendChild(a);

    addBr(element);
}

function addLink(link, element) {
    /**
     * adds a strong tag with website, a formatted link and br tagg to element
     */
    addStrong("Website", element, false);

    let a = document.createElement("a");
    a.setAttribute("href", link);
    a.setAttribute("target", "_blank");
    a.innerHTML = link;
    addBr(a, element);

    element.appendChild(a);
}

function handleStudentInformation(information) {
    /**
     * makes sure that expected properties on a student object exists
     * if they exists they are formatted and written to DOM
     */
    if (information.hasOwnProperty("name")){
        addName(information.name, output)
    }
    if (information.hasOwnProperty("email")){
        addEmail(information.email, output);
    }
    if (information.hasOwnProperty("website")){
        addLink(information.website, output);
    }
}

function handleWebsite(website, list) {
    /**
     * makes sure that expected properties on a website object exists
     * if they exists they are formatted and written to DOM as an a tag
     */
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
    /**
     * passes each website down to handleWebsite if they exist
     */
    for (let i in websites){
        if (websites.hasOwnProperty(i)){
            handleWebsite(websites[i], sites);
        }
    }
}

let request = new XMLHttpRequest();
request.addEventListener("readystatechange", function () {
    /**
     * if ready state is not 4 and http status is not 200 do nothing
     * otherwise the request data is parsed to object and checks if expected
     * properties exists in the object. if a property exists the sub object is
     * sent down a chain of function to continue processing.
     */
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
