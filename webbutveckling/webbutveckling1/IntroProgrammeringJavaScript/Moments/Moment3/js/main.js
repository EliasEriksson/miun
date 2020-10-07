"use strict";

// storing all relevant elements in global scope so they only have to be queried once.
let newToDo = document.getElementById("newtodo");
let newToDoButton = document.getElementById("newtodobutton");
let clearButton = document.getElementById("clearbutton");
let toDoList = document.getElementById("todolist");
let errorMessage = document.getElementById("message");

// cache (so local storage only have to be read and parsed once)
let cache = [];


function addToStorage(article) {
    /**
     * adds a to-do article object to the cache and updates the local storage.
     */
    cache.push(article);
    updateStorage(cache);
}

function removeFromStorage(article) {
    /**
     * removes a single article from the cache and updates the local storage.
     */
    cache.splice(cache.indexOf(article), 1);
    updateStorage();
}

function updateStorage() {
    /**
     * overwrites the local storage with the current cache.
     */
    localStorage.setItem("articles", JSON.stringify(
        cache.map(element => element.innerHTML)
    ))
}

function clearArticles() {
    /**
     * removes all articles from the DOM, cache and local storage.
     */
    toDoList.innerHTML = "";
    cache = [];
    localStorage.clear();
}

function addArticleFromText(text) {
    /**
     * adds an article element with given text to the DOM.
     *
     * if the article is clicked it is removed from DOM, cache and local storage.
     *
     * the article is returned for further processing if needed.
     */

    let article = document.createElement("article");
    let textNode = document.createTextNode(text);
    article.appendChild(textNode);

    article.addEventListener("click", function () {
        removeFromStorage(article);
        article.remove();
    })

    toDoList.appendChild(article);
    return article;
}

function inputIsValid(text) {
    /**
     * validates text and returns true if its valid
     *
     * if it returns false an error message is set to the message element.
     */
    if (text.length > 4) {
        errorMessage.innerHTML = "";
        return true;
    }
    errorMessage.innerHTML = "Ange minst fem tecken.";
    return false;
}

newToDo.addEventListener("input", function () {
    /**
     * listener for changes in the input field
     *
     * if fired check if input is valid.
     */
    inputIsValid(newToDo.value);
})

newToDoButton.addEventListener("click", function() {
    /**
     * listener for click events on the "lägg till" button
     *
     * when fired adds the input fields text to DOM,
     * cache and local storage if input validates truthy.
     */

    let text = newToDo.value;
    if (inputIsValid(text)) {
        let article = addArticleFromText(text);
        addToStorage(article);
        newToDo.value = "";
    }
});

clearButton.addEventListener("click", clearArticles);

window.addEventListener("load", function () {
    /**
     * listener for when the page is finished loading.
     *
     * adds all the stored to-dos in local storage to the DOM.
     */
    let storage = JSON.parse(localStorage.getItem("articles"));
    if (storage) {
        let article;
        for (let i = 0; i < storage.length; i++) {
            article = addArticleFromText(storage[i]);
            cache.push(article);
        }
    }

})


