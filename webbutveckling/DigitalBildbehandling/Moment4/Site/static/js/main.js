let cartButtonElement = document.getElementById("cartButton");
let cartContentElement = document.getElementById("cartContent");

let mainMenuButtonElement = document.getElementById("main-menu-button");
let mainNavigationElement = document.getElementById("main-navigation");

let searchButtonElement = document.getElementById("search-button")
let lastScroll = 0;

async function sleep (time) {
  return new Promise((resolve) => setTimeout(resolve, time));
}


// to hide the search bar
window.addEventListener("scroll", function (){
    if (window.pageYOffset > lastScroll) {
        // scroll up
        console.log("scroll down")
    } else {
        console.log("scroll up")
        // scroll down
    }
})

// show the cart when clicking
cartButtonElement.addEventListener("click", function () {
    if (cartContentElement.style.display === "none" || cartContentElement.style.display === "") {
        cartContentElement.style.display = "block";
    } else {
        cartContentElement.style.display = "none";
    }
});

// hide the card when it loses focus
cartButtonElement.addEventListener("focusout", async function () {
    await sleep(10);
    cartContentElement.style.display = "none";
});

// toggle main menu
mainMenuButtonElement.addEventListener("click", function () {
    if (mainNavigationElement.style.display === "none" || mainNavigationElement.style.display === "") {
        mainNavigationElement.style.display = "flex";
    } else {

        mainNavigationElement.style.display = "none";
    }
});

// redirect to search
searchButtonElement.addEventListener("click", function (e) {
    e.preventDefault();
    let searchFieldElement = document.getElementById("search-field");
    let searchPhrase = searchFieldElement.value;
    let newURL = window.location.href.split("?")[0]
               + searchFieldElement.parentElement.getAttribute("action")
               + `?${searchPhrase}`;
    window.location.replace(newURL);
});