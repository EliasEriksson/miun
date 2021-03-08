let root = document.currentScript.getAttribute("root");
let writeLink = document.currentScript.getAttribute("writeLink");
let baseApi = `${root}/api`;


class CluckLoader {
    constructor(getApi, id = null) {
        this.api = `${baseApi}/${getApi}/?page=`;
        this.id = id;
        this.currentPage = -1;
        this.loadingPage = -1;
        this.fullyConsumed = false;
        this.cluckElements = document.getElementById("clucks");

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
        let wrapper = createDiv("cluck-reply-count-wrapper");

        let replyCount = createSpan("cluck-reply-count");
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
        let wrapper = createDiv("cluck-publish-details");

        let publishPostTimestamp = createSpan("cluck-post-timestamp", "timestamp");
        publishPostTimestamp.innerHTML = timeAgo(parseInt(json.postDate), 10);
        wrapper.appendChild(publishPostTimestamp);

        if (json.lastEdited) {
            let publishEditTimestamp = createSpan("cluck-edit-timestamp", "timestamp");
            publishEditTimestamp.innerHTML = `(${timeAgo(parseInt(json.lastEdited), 10)})`;
            wrapper.appendChild(publishEditTimestamp);
        }

        element.appendChild(wrapper);
    }

    addMetadata(element, json) {
        let wrapper = createDiv("cluck-metadata");

        this.addPublishDetails(wrapper, json);
        this.addReplyCount(wrapper, json);

        element.appendChild(wrapper);
    }

    addContentHeadings(element, json, headingGrade) {
        let wrapper = createDiv("cluck-heading-wrapper")

        this.addCluckAndReply(wrapper, json, headingGrade)
        this.addMetadata(wrapper, json);

        element.appendChild(wrapper);
    }

    addHeadingLink(element, heading, headingGrade, link, cssClass) {

        let a = createA(cssClass);
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

        let wrapper = createDiv("cluck-and-reply");

        let url = `${root}/Profiles/Profile/?${json.userURL}`;
        let heading = `${json.firstName} ${json.lastName}`;
        this.addHeadingLink(wrapper, heading, headingGrade, url, "cluck-heading-link")

        if ((json.hasOwnProperty("repliedCluck") &&
            json.repliedCluck &&
            json.repliedCluck.hasOwnProperty("firstName") &&
            json.repliedCluck.hasOwnProperty("lastName") &&
            json.repliedCluck.hasOwnProperty("url")
        )) {
            let span = createSpan("cluck-and-reply-text");
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
