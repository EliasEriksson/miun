export const getCookies = () => {
    let cookies = {};
    document.cookie.split("; ").forEach(cookie => {
        let [key, value] = cookie.split("=");
        cookies[key] = decodeURIComponent(value);
    })
    return cookies;
}

export const writeCookies = (cookies) => {
    document.cookie = Object.keys(cookies).map(key => {
        return `${key}=${cookies[key]}`;
    }).join("; ");
}