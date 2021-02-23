function cleanURL(url) {
    return url.split("?")[0];
}

function styleCurrentMenu() {
    let menuButtonElements = document.getElementsByClassName("navigation-menu-button");
    let location = cleanURL(window.location.href);
    let url;

    for (let menuButtonElement of menuButtonElements) {
        url = cleanURL(menuButtonElement.href);
        if (location === url || location === url + "index.php") {
            menuButtonElement.classList.add("current-navigation-menu");
        }
    }
}

window.addEventListener("load", styleCurrentMenu);