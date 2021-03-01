/*
   Moment 3, Tilda Källström
*/
"use strict";

// variabler
let newToDoButton = document.getElementById("newtodobutton");
let newToDo = document.getElementById("newtodo");
let toDoListEl = document.getElementById("todolist");
let messageEl = document.getElementById("message");
let clearArticleEl = document.getElementById("todolist");
let clearButton = document.getElementById("clearbutton");

//funktioner
function saveToStorage () {
    let messages = document.querySelectorAll("#todolist article");
      console.log(messages);
    let messageArr = [];
// Loopa genom arrayen
    for(var i=0; i<messages.length; i++) {
        messageArr.push(messages[i].innerHTML);
    }

    let jsonStr = JSON.stringify(messageArr);

    localStorage.setItem("messages", jsonStr);
}

function loadMessages() {  //ladda upp todolist
    let messages = localStorage.getItem("messages");
    messages = JSON.parse(messages);

    // Rensa meddelandelistan
    toDoListEl.innerHTML = "";

    if (messages == null) {

    } else {
        for(let i=0; i<messages.length; i++) {
            // Skapa nytt element med DOM-manipulation
            let newEl = document.createElement("article");
            let newText = document.createTextNode(messages[i]);
            newEl.appendChild(newText);

            // Lägg till i todolist
            toDoListEl.appendChild(newEl);

            // Lägg till en händelsehanterare för elementet
            newEl.addEventListener("click", removeMessage, false);
        }
    }
}

function onClicked(){  //skapar ett article element när man klickar på lägg-till knappen

    let newEl = document.createElement("article");
    let newText = document.createTextNode(newToDo.value);
    newEl.appendChild(newText);

    toDoListEl.appendChild(newEl);
    newToDo.value = "";

    saveToStorage(); //sparar todolist
    loadMessages(); //laddar fram todolist vid omladdad sida

}

function validateInput(){  // kollar så att tillräckligt många bokstäver har skrivits i textfältet
    let message = newToDo.value;
    if (message.length < 5) {
        messageEl.innerHTML = "Skriv mer!"; //ber användaren skriva fler bokstäver vid för få
        newToDoButton.disabled = true;
    } else {
        messageEl.innerHTML = "";
        newToDoButton.disabled = false;
    }
}

function disableButton(){ //disabels newtodobutton när sidan laddas fram före text är skriven
    newToDoButton.disabled = true;
}

function removeMessage(e) {  // raderar skriven text
    console.log("Removing...");
    let parentNode = e.target.parentNode;

    parentNode.removeChild(e.target);
    saveToStorage(); //sparar todolist
}

 function removeToDoList(e) {       //raderar todolist
    console.log("Removing...");
   toDoListEl.innerHTML = "";
   saveToStorage(); //sparar todolist
   loadMessages(); //laddar fram todolist vid omladdad sida
}

 //händelsehanterare
newToDoButton.addEventListener("click", onClicked, false); // click för när man klickar på knappen
newToDo.addEventListener("keyup", validateInput, false); // keyup gäller om en tangent trycks ned
window.addEventListener("load", disableButton, false); // load för när sidan laddas upp
clearButton.addEventListener("click", removeToDoList, false);
window.addEventListener("load", loadMessages, false);


