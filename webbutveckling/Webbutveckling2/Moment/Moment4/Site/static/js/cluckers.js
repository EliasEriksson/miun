/**
 * loads users from one of the API end points that receives users
 *
 * all add methods in this class is used t create an HTML structure that is written to DOM
 */

class CluckerLoader {
    constructor(getApi, root, writeLink) {
        this.root = root;
        this.writeLink = writeLink;
        this.api = `${this.root}/api/${getApi}/?page=`;
        this.currentPage = -1;
        this.loadingPage = -1;
        this.fullyConsumed = false;
        this.cluckerElements = document.getElementById("cluckers");
    }

    addAvatar(element, json) {
        let wrapper = createDiv("cluckers-avatar-wrapper");
        if (!json.hasOwnProperty("avatar")) {
            throw new ReferenceError();
        }

        let img = document.createElement("img");
        img.classList.add("cluck-avatar");
        img.alt = "Cluck user avatar";
        img.src = `${this.writeLink}${json.avatar}`;

        wrapper.appendChild(img)
        element.appendChild(wrapper)
    }


    addMetadata(element, json) {
        if (!(json.hasOwnProperty("postCount") &&
            json.hasOwnProperty("replyCount"))) {
            throw new ReferenceError("missing properties.");
        }

        let wrapper = createDiv("clucker-metadata");

        if (json.postCount) {
            let cluckerTotalPostsElement = createSpan("clucker-total-posts");
            let unit = (json.postCount === 1) ? "gång": "gånger";
            cluckerTotalPostsElement.innerHTML = `Kacklat: ${json.postCount} ${unit}.`
            wrapper.appendChild(cluckerTotalPostsElement);
        }
        if (json.replyCount) {
            let cluckerTotalRepliesElement = createSpan("clucker-total-replies");
            let unit = (json.replyCount === 1) ? "gång": "gånger";
            cluckerTotalRepliesElement.innerHTML = `Svarad: ${json.replyCount} ${unit}.`;
            wrapper.appendChild(cluckerTotalRepliesElement);
        }

        element.appendChild(wrapper);
    }

    addHeading(element, json, headingGrade) {
        if (!(json.hasOwnProperty("firstName") &&
                json.hasOwnProperty("lastName") &&
                json.hasOwnProperty("url"))) {
            throw new ReferenceError();
        }

        let wrapper = createA("cluck-heading-link");
        wrapper.href = `${this.root}/Profiles/Profile/?${json.url}`;

        let hElement = document.createElement(`h${headingGrade}`);
        hElement.innerHTML = `${json.firstName} ${json.lastName}`;

        wrapper.appendChild(hElement);
        element.appendChild(wrapper)
    }

    addHeadingWrapper(element, json, headingGrade) {
        let wrapper = createDiv("clucker-heading-wrapper");

        this.addHeading(wrapper, json, headingGrade);
        this.addMetadata(wrapper, json);

        element.appendChild(wrapper);
    }

    addDescription(element, json) {
        if (!json.hasOwnProperty("description")) {
            throw new ReferenceError();
        }
        let wrapper = createDiv("cluckers-description-wrapper");

        let pElement = document.createElement("p");
        pElement.innerHTML = json.description;

        wrapper.appendChild(pElement);
        element.appendChild(wrapper);
    }

    addUsers(json, headingGrade) {
        for (let i = 0; i < json.length; i++) {
            try {
                if (!json[i].hasOwnProperty("url")) {
                    continue;
                }

                let wrapper = createDiv("clucker");
                makeClickable(wrapper, `${this.root}/Profiles/Profile/?${json[i].url}`);

                this.addAvatar(wrapper, json[i]);
                this.addHeadingWrapper(wrapper, json[i], headingGrade);
                this.addDescription(wrapper, json[i]);

                this.cluckerElements.appendChild(wrapper);

            } catch (e) {
                if (!(e instanceof ReferenceError)) {
                    throw e;
                }
                console.log(e);
            }
        }


    }

    /**
     * generates the next URL to request
     *
     * @returns {string}
     */
    nextPageURL() {
        return this.api + ++this.loadingPage;
    }

    /**
     * requests the get API and adds users to the DOM.
     *
     * if the json array received is empty its assumed that there is no more data to get
     * and fullConsumed is set to true
     *
     * @param headingGrade
     * @returns {Promise<void>}
     */
    async fetchUsers(headingGrade) {
        let response = await fetch(this.nextPageURL());
        if (response.status !== 200) {
            return;
        }

        let json = await response.json();
        if (!json.length) {
            this.fullyConsumed = true;
        }

        this.addUsers(json, headingGrade);
        this.currentPage++;
    }

    /**
     * attempts to load more users if conditions to do so are met
     *
     * to load more users on of the bottom 5 users must be in the users viewport.
     * the requested api have not yet returned an empty array.
     * there must not already be an ongoing request to the API
     *
     * @param headingGrade
     * @returns {Promise<void>}
     */
    async loadUsers(headingGrade = 2) {
        if (this.fullyConsumed || this.loadingPage !== this.currentPage) {
            return;
        }
        if (this.cluckerElements.childElementCount === 0) {
            await this.fetchUsers(headingGrade);
        } else {
            for (let i = this.cluckerElements.childElementCount - 5; i < this.cluckerElements.childElementCount; i++) {
                if (this.cluckerElements.children.hasOwnProperty(i) && isInViewport(this.cluckerElements.children[i])) {
                    await this.fetchUsers(headingGrade);
                    return;
                }
            }
        }
    }
}