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

function makeClickable(element, link) {
    element.addEventListener("click", (event) => {
        event.preventDefault();
        let parent = event.target.parentElement;
        if (parent.href) {
            window.location.href = parent.href;
        } else {
            window.location.href = `${root}/Cluck/?${link}`;
        }
    });
}

class CluckLoader {
    constructor(getApi, id = null) {
        this.api = `${baseApi}/${getApi}/?page=`;
        this.id = id;
        this.currentPage = -1;
        this.loadingPage = -1;
        this.fullyConsumed = false;
        this.cluckElements = document.getElementById("clucks");
        if (!this.cluckElements) {
            this.fullyConsumed = true;
        }
    }

    createDiv(...classes) {
        let element = document.createElement("div");
        for (let i = 0; i < classes.length; i++) {
            element.classList.add(classes[i]);
        }
        return element;
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

        let img = document.createElement("img");
        img.classList.add("cluck-avatar");
        img.alt = "Cluck user avatar";
        img.src = `${writeLink}${json.avatar}`;

        element.appendChild(img);
    }

    addReplyCount(element, json) {
        if (!json.hasOwnProperty("replyCount")) {
            throw new ReferenceError("Undefined property replyCount.");
        }
        if (!json.replyCount) {
            return;
        }
        let wrapper = this.createDiv("cluck-reply-count-wrapper");

        let replyCount = this.createSpan("cluck-reply-count");
        replyCount.innerHTML = `${json.replyCount} svar`;

        wrapper.appendChild(replyCount);
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

    addMetadata(element, json) {
        let wrapper = this.createDiv("cluck-metadata");

        this.addPublishDetails(wrapper, json);
        this.addReplyCount(wrapper, json);

        element.appendChild(wrapper);
    }

    addContentHeadings(element, json, headingGrade) {
        let wrapper = this.createDiv("cluck-heading-wrapper")

        this.addCluckAndReply(wrapper, json, headingGrade)
        this.addMetadata(wrapper, json);

        element.appendChild(wrapper);
    }

    addHeadingLink(element, heading, headingGrade, link, cssClass) {

        let a = this.createA(cssClass);
        a.href = link;

        let h = document.createElement(`h${headingGrade}`);
        h.innerHTML = heading;

        a.appendChild(h);
        element.appendChild(a);
    }

    addCluckAndReply(element, json, headingGrade) {
        if (!(json.hasOwnProperty("userURL") &&
            json.hasOwnProperty("firstName") &&
            json.hasOwnProperty("lastName"))) {
            throw new ReferenceError("missing data")
        }

        let wrapper = this.createDiv("cluck-and-reply");

        let url = `${root}/Profiles/Profile/?${json.userURL}`;
        let heading = `${json.firstName} ${json.lastName}`;
        this.addHeadingLink(wrapper, heading, headingGrade, url, "cluck-heading-link")

        if ((json.hasOwnProperty("repliedCluck") &&
            json.repliedCluck &&
            json.repliedCluck.hasOwnProperty("firstName") &&
            json.repliedCluck.hasOwnProperty("lastName") &&
            json.repliedCluck.hasOwnProperty("url")
        )) {
            let span = this.createSpan("cluck-and-reply-text");
            span.innerHTML = "svarar";
            wrapper.appendChild(span);

            heading = `${json.repliedCluck.firstName} ${json.repliedCluck.lastName}`;
            url = `${root}/Cluck/?${json.repliedCluck.url}`
            this.addHeadingLink(wrapper, heading, headingGrade + 1, url, "cluck-heading-link");
        }

        element.appendChild(wrapper);
    }

    addContent(element, json) {
        if (!json.hasOwnProperty("content")) {
            throw ReferenceError("Undefined property content.")
        }

        let content = document.createElement("p");
        content.classList.add("cluck-content");
        content.innerHTML = json.content;

        element.appendChild(content);
    }

    addClucks(json, headingGrade) {
        for (let i = 0; i < json.length; i++) {
            try {
                if (!json[i].hasOwnProperty("url")) {
                    continue;
                }
                let cluckElement = document.createElement("article");
                cluckElement.classList.add("cluck");
                makeClickable(cluckElement, json[i].url);

                this.addAvatar(cluckElement, json[i]);
                this.addContentHeadings(cluckElement, json[i], headingGrade);
                this.addContent(cluckElement, json[i]);

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
        if (this.id) {
            return `${this.api}${++this.loadingPage}&id=${this.id}`;
        }
        return this.api + ++this.loadingPage;
    }

    async fetchClucks(headingGrade) {
        let response = await fetch(this.nextPageURL());
        if (response.status !== 200) {
            return;
        }

        let json = await response.json();
        if (!json.length) {
            this.fullyConsumed = true;
        }
        this.addClucks(json, headingGrade);
        this.currentPage++
    }

    async loadClucks(headingGrade = 2) {
        if (this.fullyConsumed || this.loadingPage !== this.currentPage) {
            return;
        }

        if (this.cluckElements.childElementCount === 0) {
            await this.fetchClucks(headingGrade);
        } else {
            for (let i = this.cluckElements.childElementCount - 5; i < this.cluckElements.childElementCount; i++) {
                if (this.cluckElements.children.hasOwnProperty(i) && isInViewport(this.cluckElements.children[i])) {
                    await this.fetchClucks(headingGrade);
                    return;
                }
            }
        }
    }
}
