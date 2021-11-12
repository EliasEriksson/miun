import {getCookies} from "./cookie.js";


/**
 * request file from the template directory.
 *
 * this template will be used with the render function in xrender.ts
 *
 * @param templateName: filename of the template.
 */
export const requestTemplate = async (templateName) => {
    let response = await fetch(`${templateName}`);
    return await response.text();
};


/**
 * a general function to preform GET / POST / PUT / DELETE request.
 *
 * necessary headers will be set as needed.
 *
 * @param endpoint: the api endpoint to request
 * @param token: the authentication token. required for POST / PUT / DELETE.
 * @param method: the request method.
 * @param data: general data to be sent with the request.
 */
export const requestEndpoint = async (endpoint, token = null, method = "GET", data = undefined) => {
    let apiURL = getCookies()["apiRoot"];
    let init = {
        method: method,
        headers: {
            "Content-Type": "application/json"
        }
    };
    if (token) {
        init.headers["Authorization"] = `Token ${token}`;
    }
    if (data) {
        init["body"] = JSON.stringify(data);
    }
    let response = await fetch(`${apiURL}${endpoint}`, init);

    if (200 <= response.status && response.status < 300) {
        return [await response.json(), response.status];
    }
    return [await response.text(), response.status];
};