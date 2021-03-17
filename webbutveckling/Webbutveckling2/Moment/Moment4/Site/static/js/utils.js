/**
 * adds an event listener on the element and redirects the user to given URL
 * unless the clicked elements parent is an <a>
 *
 * @param element
 * @param link
 */
function makeClickable(element, link) {
    element.addEventListener("click", (event) => {
        event.preventDefault();
        let parent = event.target.parentElement;
        if (parent.href) {
            window.location.href = parent.href;
        } else {
            window.location.href = link;
        }
    });
}

/**
 * checks if the element is rendered in the window
 *
 * @param element
 * @returns {boolean}
 */
function isInViewport(element) {
    let rect = element.getBoundingClientRect();
    return (
        rect.top >= 0 &&
        rect.left >= 0 &&
        rect.bottom <= window.innerHeight &&
        rect.right <= window.innerWidth
    );
}

/**
 * creates a div element with given css classes
 *
 * @param classes
 * @returns {HTMLDivElement}
 */
function createDiv(...classes) {
    let element = document.createElement("div");
    for (let i = 0; i < classes.length; i++) {
        element.classList.add(classes[i]);
    }
    return element;
}

/**
 * creates a span element with given CSS classes
 *
 * @param classes
 * @returns {HTMLSpanElement}
 */
function createSpan(...classes) {
    let element = document.createElement("span");
    for (let i = 0; i < classes.length; i++) {
        element.classList.add(classes[i]);
    }
    return element;
}

/**
 * creates an a element with given CSS classes
 *
 * @param classes
 * @returns {HTMLAnchorElement}
 */
function createA(...classes) {
    let element = document.createElement("a");
    for (let i = 0; i < classes.length; i++) {
        element.classList.add(classes[i]);
    }
    return element;
}
