import {requestEndpoint} from "./modules/requests.js";

const form = document.getElementById("course-form");

window.addEventListener("load", async () => {

    form.addEventListener("submit", async () => {
        await requestEndpoint("/course/")
    });
});