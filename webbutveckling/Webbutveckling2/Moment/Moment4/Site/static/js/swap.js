/**
 * this script handles the mobile view of the home page
 *
 * it allows the user to flip from displaying posts to displaying users when a button is clicked
 * on the home screen for non desktop users
 *
 */

let enabled = false;
let clucksShown = true;

let clucks = document.getElementById("cluck-section");
let cluckers = document.getElementById("clucker-section");

let cluckButtonElement = document.getElementById("cluck-button");
let cluckerButtonElement = document.getElementById("clucker-button");

function show(element) {
    element.classList.remove("hide");

}

function hide(element) {
    element.classList.add("hide");
}

cluckButtonElement.addEventListener("click", ()=>{
    hide(clucks);
    show(cluckers);
    show(cluckerButtonElement);
    clucksShown = false;
});

cluckerButtonElement.addEventListener("click", ()=>{
    hide(cluckers);
    show(clucks);
    show(cluckButtonElement);
    clucksShown = true;
});

function evaluate() {
    if (window.innerWidth - 960 < 0 && !enabled) {
        enabled = true;
        if (clucksShown) {
            console.log("show clucks");
            show(cluckButtonElement);
            hide(cluckers);
        } else {
            console.log("show cluckers");
            show(cluckerButtonElement);
            hide(clucks);
        }
    }

    if (window.innerWidth - 960 > 0 && enabled) {
        enabled = false;
        hide(cluckerButtonElement);
        hide(cluckButtonElement);
        show(clucks);
        show(cluckers);
    }
}

window.addEventListener("resize", evaluate);
window.addEventListener("load", evaluate);