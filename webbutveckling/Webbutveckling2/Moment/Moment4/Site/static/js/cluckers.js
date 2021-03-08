let root = document.currentScript.getAttribute("root");
let writeLink = document.currentScript.getAttribute("writeLink");
let baseApi = `${root}/api`;


class CluckerLoader {
    constructor(getApi) {
        this.api = `${baseApi}/${getApi}/?page=`;
        this.currentPage = -1;
        this.loadingPage = -1;
        this.fullyConsumed = false;
        this.cluckerElements = document.getElementById("cluckers");
    }

    addAvatar(element, json) {
        if (!json.hasOwnProperty("avatar")) {
            throw new ReferenceError("missing avatar");
        }

        let img = document.createElement("img");
        img.classList.add("cluck-avatar");
        img.alt = "Cluck user avatar";
        img.src = `${writeLink}${json.avatar}`;

        element.appendChild(img)
    }



}