"use strict";

// storing all relevant elements in global scope so they only have to be queried once.
let newToDo = document.getElementById("newtodo");
let newToDoButton = document.getElementById("newtodobutton");
let clearButton = document.getElementById("clearbutton");
let toDoList = document.getElementById("todolist");
let errorMessage = document.getElementById("message");

// loads all stored to-dos in memory
let storedToDos = JSON.parse(localStorage.getItem("articles"));
if (!storedToDos) {
    storedToDos = [];
}

function storeToDo(text) {
    /**
     * adds a to-do to the memory and save the memory to localstorage.
     */
    storedToDos.push(text);
    localStorage.setItem("articles", JSON.stringify(storedToDos));
}

function clearToDos() {
    /**
     * removes all todos from memory, the website and stores null to the localstorage.
     */
    toDoList.innerHTML = "";
    localStorage.setItem("articles", null);
    storedToDos = [];
}

function addToDo(text) {
    /**
     * adds a to-do to the website
     *
     * the to-do item also gets an anonymous function for its onclick event
     * that removes the item from the website, memory and localstorage if clicked.
     */
    let articleNode = document.createElement("article");
    articleNode.addEventListener("click", () => {
        storedToDos.splice(storedToDos.indexOf(text), 1);
        localStorage.setItem("articles", JSON.stringify(storedToDos));
        articleNode.remove();
    })

    let textNode = document.createTextNode(text);
    articleNode.appendChild(textNode);
    toDoList.appendChild(articleNode);
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

newToDo.addEventListener("input", () => {
    /**
     * listener for changes in teh input field
     *
     * if fired check if input is valid.
     */
    inputIsValid(newToDo.value);
})

newToDoButton.addEventListener("click", () => {
    /**
     * listener for click events on the "lägg till" button
     *
     * when fired adds the input fields text to website,
     * memory and local storage if input validates.
     */
    let text = newToDo.value;
    if (inputIsValid(text)) {
        addToDo(text);
        storeToDo(text);
        newToDo.value = "";
    }
});

clearButton.addEventListener("click", () => {
    /**
     * listener for click events on the "rensa" button
     *
     * when fired all the to-dos are cleared from the website,
     * memory and localstorage.
     */
    clearToDos();
})

window.addEventListener("load", () => {
    /**
     * listener for when the page is finished loading.
     *
     * adds all the stored to-dos in localstorage to the site.
     */
    for (let i = 0; i < storedToDos.length; i++) {
        addToDo(storedToDos[i]);
    }
})

