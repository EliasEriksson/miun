"use strict";

console.log("loaded JS file.")

window.addEventListener("load", (_) => {
    console.log("page loaded.");
});

let newToDoButton = document.getElementById("newtodobutton");
newToDoButton.onclick = () => {
    let newToDo = document.getElementById("newtodo");
    console.log(newToDo);
    alert("wat?")
}

let clearButton = document.getElementById("clearbutton");
newToDoButton.onclick = () => {

}
