"use strict";

let newToDo = document.getElementById("newtodo");
let newToDoButton = document.getElementById("newtodobutton");
let clearButton = document.getElementById("clearbutton");
let toDoList = document.getElementById("todolist");
let errorMessage = document.getElementById("message");

let storedToDos = JSON.parse(localStorage.getItem("articles"));
if (!storedToDos) {
    storedToDos = [];
}

function storeToDo(text) {
    storedToDos.push(text);
    localStorage.setItem("articles", JSON.stringify(storedToDos));
}

function removeToDo(text) {
    storedToDos.splice(storedToDos.indexOf(text), 1);
    localStorage.setItem("articles", JSON.stringify(storedToDos));
}

function clearToDos() {
    toDoList.innerHTML = "";
    localStorage.setItem("articles", null);
    storedToDos = [];
}

function addToDo(text) {
    let articleNode = document.createElement("article");
    articleNode.addEventListener("click", () => {
        removeToDo(text)
        articleNode.remove();
    })

    let textNode = document.createTextNode(text);
    articleNode.appendChild(textNode);
    toDoList.appendChild(articleNode);
}

function inputIsValid(text) {
    if (text.length > 4) {
        errorMessage.innerHTML = "";
        return true;
    }
    errorMessage.innerHTML = "Ange minst fem tecken.";
    return false;
}

newToDo.addEventListener("input", () => {
    inputIsValid(newToDo.value);
})

newToDoButton.addEventListener("click", () => {
    let text = newToDo.value;
    if (inputIsValid(text)) {
        addToDo(text);
        storeToDo(text);
        newToDo.value = "";
    }
});

clearButton.addEventListener("click", () => {
    clearToDos();
})

window.addEventListener("load", () => {
    for (let i = 0; i < storedToDos.length; i++) {
        addToDo(storedToDos[i]);
    }
})

