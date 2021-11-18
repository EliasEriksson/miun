import {requestEndpoint} from "./modules/requests.js";
import {redirect} from "./modules/url.js";
import {getCookies} from "./modules/cookie.js";

const formElement = document.getElementById("course-form");
const putElement = formElement.querySelector("[name=update-button]");
const delElement = formElement.querySelector("[name=delete-button]");


/**
 * searches the URL for an id.
 *
 * @returns {string}
 */
const getIdFromURL = () => {
    let parts = location.href.split("/");
    if (parts[parts.length - 1]) {
        return parts[parts.length - 1];
    }
    return parts[parts.length - 2];
}


/**
 * Sends a PUT request to the course endpoint.
 *
 * @returns {number}
 */
const put = async () => {
    let id = getIdFromURL();
    let [_, status] = await requestEndpoint(`/courses/${id}/`, null, "put",
        Object.fromEntries(new FormData(formElement).entries())
    );
    return status;
}


/**
 * Sends a DELETE request to the course endpoint.
 *
 * @returns {number}
 */
const del = async () => {
    let id = getIdFromURL();
    let [_, status] = await requestEndpoint(`/courses/${id}/`, null, "delete");
    return status;
}

window.addEventListener("load", async () => {
    let cookies = getCookies();
    // prevent the form from being submitted
    formElement.addEventListener("submit", async e => {
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