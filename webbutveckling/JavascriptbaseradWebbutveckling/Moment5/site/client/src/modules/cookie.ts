
export interface Cookies {
    [key: string]: string,
    apiURL: string,
    rootURL: string
}

/**
 * converts the document cookie string to a javascript object
 *
 */
export const getCookies = (): Cookies => {
    let cookies: any = {};
    document.cookie.split("; ").forEach(cookie => {
        let [key, value] = cookie.split("=");
        cookies[key] = decodeURIComponent(value);
    })
    return cookies;
}