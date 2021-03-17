/**
 * adds CSS styling to the navigation elements that links to the same page the user is on.
 * used to show the user that "you are on this page".
 */

function cleanURL(url) {
    return url.split("?")[0];
}

function styleCurrentMenu() {
    let menuButtonElements = document.getElementsByClassName("navigation-button");
    let location = cleanURL(window.location.href);
    let url;
    for (let menuButtonElement of menuButtonElements) {
        url = cleanURL(menuButtonElement.href);
        if (location === url || location === url + "index.php") {
            menuButtonElement.classList.add("current-navigation-button");
        }
    }
}

window.addEventListener("load", styleCurrentMenu);