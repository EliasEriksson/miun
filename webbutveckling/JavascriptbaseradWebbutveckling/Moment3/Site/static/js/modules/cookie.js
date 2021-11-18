/**
 * converts the document cookie string to a javascript object
 *
 * @returns {{}}
 */
export const getCookies = () => {
    let cookies = {};
    document.cookie.split("; ").forEach(cookie => {
        let [key, value] = cookie.split("=");
        cookies[key] = decodeURIComponent(value);
    })
    return cookies;
}
