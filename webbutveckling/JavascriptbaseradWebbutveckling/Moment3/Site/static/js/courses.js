import {getCookies} from "./modules/cookie.js";
import {requestEndpoint, requestTemplate} from "./modules/requests.js";
import {render} from "./modules/xrender.js";


const formElement = document.getElementById("course-form");
const postElement = formElement.querySelector("[name=post-button]")
const tableBodyElement = document.getElementById("result-data");



/**
 * requests data from the server and renders all the courses from the "database" file.
 *
 * @returns {Promise<void>}
 */
const getAll = async () => {
    let cookies = getCookies();
    let templateP = requestTemplate(`${cookies["staticRoot"]}/templates/courseRow.html`);
    let coursesP = requestEndpoint(`/courses/`);

    let template = await templateP;
    let [courses, status] = await coursesP;
    if (200 <= status && status < 300) {
        for (const course of courses) {
            let spacer = document.createElement("div");
            spacer.classList.add("spacer");
            course["link"] = `${cookies["rootURL"]}/courses/${course._id}/`;
            tableBodyElement.appendChild(spacer);
            tableBodyElement.appendChild(render(template, course));
        }
    }
}

/**
 * posts the current form data and attempts to create a new course
 *
 * @returns {Promise<void>}
 */
const post = async () => {
    await requestEndpoint(`/courses/`, null, "post",
        Object.fromEntries(new FormData(formElement).entries())
    );
}

window.addEventListener("load", async () => {
    formElement.addEventListener("submit", e => {
        e.preventDefault();
    });

    postElement.addEventListener("click", async e => {
        e.preventDefault();
        await post();
        location.reload();
    });

    await getAll();
});