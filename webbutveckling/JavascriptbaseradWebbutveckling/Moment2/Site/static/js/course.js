import {requestEndpoint} from "./modules/requests.js";
import {redirect} from "./modules/url.js";
import {getCookies} from "./modules/cookie.js";

const form = document.getElementById("course-form");
const courseIdElement = form.querySelector("[name=courseId]");
const courseNameElement = form.querySelector("[name=courseName]");
const coursePeriodElement = form.querySelector("[name=coursePeriod]");
const putElement = form.querySelector("[name=update-button]");
const delElement = form.querySelector("[name=delete-button]");


/**
 * searches the URL for an id.
 *
 * @returns {number}
 */
const getIdFromURL = () => {
    let parts = location.href.split("/");
    if (parts[parts.length - 1]) {
        return parseInt(parts[parts.length - 1]);
    }
    return parseInt(parts[parts.length - 2]);
}


/**
 * Reads data from DOM elements to an object.
 *
 * @returns {{courseName, coursePeriod, courseId}}
 */
const getCurrentFormCourse = () => {
    return {
        courseId: courseIdElement.value,
        courseName: courseNameElement.value,
        coursePeriod: coursePeriodElement.value
    }
}


/**
 * Sends a PUT request to the course endpoint.
 *
 * @returns {number}
 */
const put = async () => {
    let id = getIdFromURL();
    let [_, status] = await requestEndpoint(`/course/${id}/`, null, "put", {
        ...getCurrentFormCourse()
    });
    return status;
}


/**
 * Sends a DELETE request to the course endpoint.
 *
 * @returns {number}
 */
const del = async () => {
    let id = getIdFromURL();
    let [_, status] = await requestEndpoint(`/course/${id}/`, null, "delete");
    return status;
}

window.addEventListener("load", async () => {
    let cookies = getCookies();
    // prevent the form from being submitted
    form.addEventListener("submit", async e => {
        e.preventDefault();
    });

    // sends the PUT request and if it is successful
    // redirect back to root
    putElement.addEventListener("click", async e => {
        e.preventDefault();
        let status = await put();
        if (200 <= status && status < 300) {
            redirect(cookies["rootURL"]);
        }
    });

    // sends the DELETE request and if it is successful
    // redirect back to root
    delElement.addEventListener("click", async e => {
        e.preventDefault();
        let status = await del();
        if (200 <= status && status < 300) {
            redirect(cookies["rootURL"]);
        }
    });
});