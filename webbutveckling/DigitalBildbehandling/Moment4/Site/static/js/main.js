let cartButtonElement = document.getElementById("cartButton");
let cartContentElement = document.getElementById("cartContent");
let emptyBasketButton = document.getElementById("emptyBasketButton");

let mainMenuButtonElement = document.getElementById("main-menu-button");
let mainNavigationElement = document.getElementById("main-navigation");

let searchButtonElement = document.getElementById("search-button")

async function sleep(ms) {
  return new Promise(resolve => setTimeout(resolve, ms));
}

// show the cart when clicking
cartButtonElement.addEventListener("click",     function (e) {
    e.preventDefault();
    if (cartContentElement.style.display === "none" || cartContentElement.style.display === "") {
        cartContentElement.style.display = "block";
    } else {
        cartContentElement.style.display = "none";
    }
});

// hide the card when it loses focus
cartButtonElement.addEventListener("blur", async function () {
   await sleep(200);
    cartContentElement.style.display = "none";
});

emptyBasketButton.addEventListener("click", function (e) {
    e.preventDefault();
    emptyBasket();
})

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
    let searchPhrase = searchFieldElement.value.toLowerCase();
    let newURL = window.location.href.split("?")[0]
               + searchFieldElement.parentElement.getAttribute("action")
               + `?${searchPhrase}`;
    window.location.replace(newURL);
});
