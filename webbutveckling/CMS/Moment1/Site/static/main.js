let burgerElement = document.getElementById("burger");
let menuElement = document.getElementById("main-nav");


burgerElement.addEventListener("click", () => {
    if (menuElement.style.display === "flex") {
        menuElement.style.display = "none";
    } else {
        menuElement.style.display = "flex";
    }
    console.log(menuElement.style.display);
});

window.addEventListener("resize", () => {
    if (!(window.matchMedia('(max-width: 500px)')).matches) {
        menuElement.style.display = "flex";
    }
});