let root = document.currentScript.getAttribute("root");
let writeLink = document.currentScript.getAttribute("writeLink");
let baseApi = `${root}/api`

function isInViewport(element) {
    let rect = element.getBoundingClientRect();
    return (
        rect.top >= 0 &&
        rect.left >= 0 &&
        rect.bottom <= window.innerHeight &&
        rect.right <= window.innerWidth
    );
}

class CluckLoader {
    constructor(getApi) {
        this.api = `${baseApi}/${getApi}/?page=`;
        this.currentPage = -1;
        this.loadingPage = -1;
        this.fullyConsumed = false;
        this.cluckElements = document.getElementById("clucks");
    }

    createDiv(...classes) {
        let element = document.createElement("div");
        for (let i = 0; i < classes.length; i++) {
            element.classList.add(classes[i]);
        }
        return element;
    }

    makeClickable(element, link) {
        element.addEventListener("click", (event) => {
            event.preventDefault();
            let parent = event.target.parentElement;
            console.log(parent);
            if (parent.href) {
                window.location.href = parent.href;
            }
            window.location.href = `${root}/Cluck/?${link}`;
        });
    }

    createSpan(...classes) {
        let element = document.createElement("span");
        for (let i = 0; i < classes.length; i++) {
            element.classList.add(classes[i]);
        }
        return element;
    }

    createA(...classes) {
        let element = document.createElement("a");
        for (let i = 0; i < classes.length; i++) {
            element.classList.add(classes[i]);
        }
        return element;
    }

    addAvatar(element, json) {
        if (!json.hasOwnProperty("avatar")) {
            throw new ReferenceError("Undefined property avatar.")
        }
        let wrapper = this.createDiv("cluck-user-Profile-image-wrapper");

        let img = document.createElement("img");
        img.classList.add("cluck-user-Profile-image");
        img.alt = "Cluck user avatar";
        img.src = `${writeLink}${json.avatar}`;
        wrapper.appendChild(img);

        element.appendChild(wrapper);
    }

    addPublishDetails(element, json) {
        if (!json.hasOwnProperty("postDate")) {
            throw ReferenceError("Undefined property postDate.");
        }
        if (!json.hasOwnProperty("lastEdited")) {
            throw ReferenceError("Undefined property lastEdited.")
        }
        let wrapper = this.createDiv("cluck-publish-details");

        let publishPostTimestamp = this.createSpan("cluck-post-timestamp", "timestamp");
        publishPostTimestamp.innerHTML = timeAgo(parseInt(json.postDate), 10);
        wrapper.appendChild(publishPostTimestamp);

        if (json.lastEdited) {
            let publishEditTimestamp = this.createSpan("cluck-edit-timestamp", "timestamp");
            publishEditTimestamp.innerHTML = `(${timeAgo(parseInt(json.lastEdited), 10)})`;
            wrapper.appendChild(publishEditTimestamp);
        }

        element.appendChild(wrapper);
    }

    addContentHeadings(element, json) {
        if (!json.hasOwnProperty("firstName")) {
            throw ReferenceError("Undefined property firstName.");
        }
        if (!json.hasOwnProperty("lastName")) {
            throw ReferenceError("Undefined property lastName.");
        }
        if (!json.hasOwnProperty("url")) {
            throw ReferenceError("Undefined property url.");
        }
        if (!json.hasOwnProperty("userURL")) {
            throw ReferenceError("Undefined property userURL.")
        }
        let wrapper = this.createDiv("cluck-heading-wrapper")

        let link = this.createA("cluck-heading-link");
        link.href = `${root}/Profiles/Profile/?${json.userURL}`;

        let heading = document.createElement("h2");
        heading.innerHTML = `${json.firstName} ${json.lastName}`;
        link.appendChild(heading);

        wrapper.appendChild(link);
        this.addPublishDetails(wrapper, json);

        element.appendChild(wrapper);
    }

    addContent(element, json) {
        if (!json.hasOwnProperty("content")) {
            throw ReferenceError("Undefined property content.")
        }
        let wrapper = this.createDiv("cluck-content-wrapper");

        let content = document.createElement("p");
        content.classList.add("cluck-content");
        content.innerHTML = json.content;
        wrapper.appendChild(content);

        element.appendChild(wrapper);
    }

    addBody(element, json) {
        let wrapper = this.createDiv("cluck-body-wrapper");

        this.addContentHeadings(wrapper, json);
        this.addContent(wrapper, json);

        element.appendChild(wrapper);
    }

    addClucks(json) {
        for (let i = 0; i < json.length; i++) {
            try {
                if (!json[i].hasOwnProperty("url")) {
                    continue;
                }
                let cluckElement = document.createElement("article");
                cluckElement.classList.add("cluck");
                this.makeClickable(cluckElement, json[i].url);

                this.addAvatar(cluckElement, json[i]);
                this.addBody(cluckElement, json[i]);

                this.cluckElements.appendChild(cluckElement);
            } catch (e) {
                if (!(e instanceof ReferenceError)) {
                    throw e;
                }
                console.log(e);
            }

        }
    }

    nextPageURL() {
        return this.api + ++this.loadingPage;
    }

    async fetchClucks() {
        let response = await fetch(this.nextPageURL());
        if (response.status !== 200) {
            return;
        }

        let json = await response.json();
        if (!json.length) {
            this.fullyConsumed = true;
        }

        this.addClucks(json);
        this.currentPage++
    }

    async loadClucks() {
        if (this.fullyConsumed || this.loadingPage !== this.currentPage) {
            return;
        }

        if (this.cluckElements.childElementCount === 0) {
            await this.fetchClucks();
        } else {
            for (let i = this.cluckElements.childElementCount - 5; i < this.cluckElements.childElementCount; i++) {
                if (this.cluckElements.children.hasOwnProperty(i) && isInViewport(this.cluckElements.children[i])) {
                    await this.fetchClucks();
                    return;
                }
            }
        }
    }
}
