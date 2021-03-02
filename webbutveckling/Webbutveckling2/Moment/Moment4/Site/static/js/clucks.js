let baseApi = `${document.currentScript.getAttribute("root")}/api`


class CluckLoader {
    constructor(getApi) {
        this.api = `${baseApi}/${getApi}/?page=`;
        this.currentPage = -1;
        this.loadingPage = -1;
        this.fullyConsumed = false;
        this.cluckElements = document.getElementById("clucks");
    }

    nextPageURL() {
        return this.api + ++this.loadingPage;
    }

    async getClucks() {
        if (this.loadingPage !== this.currentPage) {
            return;
        }
        let response = await fetch(this.nextPageURL());
        if (response.status !== 200) {
            return;
        }
        let data = await response.json();
        console.log(data);
    }
}

let cluckLoader = new CluckLoader("getHotClucks");

window.addEventListener("load", async ()=>{
    await cluckLoader.getClucks()
});
