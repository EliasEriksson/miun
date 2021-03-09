function isInViewport(element) {
    let rect = element.getBoundingClientRect();
    return (
        rect.top >= 0 &&
        rect.left >= 0 &&
        rect.bottom <= window.innerHeight &&
        rect.right <= window.innerWidth
    );
}

function createDiv(...classes) {
    let element = document.createElement("div");
    for (let i = 0; i < classes.length; i++) {
        element.classList.add(classes[i]);
    }
    return element;
}

function createSpan(...classes) {
    let element = document.createElement("span");
    for (let i = 0; i < classes.length; i++) {
        element.classList.add(classes[i]);
    }
    return element;
}

function createA(...classes) {
    let element = document.createElement("a");
    for (let i = 0; i < classes.length; i++) {
        element.classList.add(classes[i]);
    }
    return element;
}
