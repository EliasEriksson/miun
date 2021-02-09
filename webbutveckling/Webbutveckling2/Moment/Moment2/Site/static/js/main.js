let descriptionElement = document.getElementById("description");
let toDoElements = document.getElementsByClassName("todo");


function adjustDescriptionSize(element) {
    element.style.height = "0px";
    let styles = window.getComputedStyle(element);
    let height = parseInt(styles.getPropertyValue("border-top"), 10)
        + parseInt(styles.getPropertyValue("padding-bottom"), 10)
        + element.scrollHeight
        + parseInt(styles.getPropertyValue("padding-top"), 10)
        + parseInt(styles.getPropertyValue("border-bottom"), 10);
    element.style.height = height+"px";
}

function maxCharacters(element, n) {
    if (element.value.length > n) {
        element.value = element.value.substring(0, n)
    } else if (isNaN(element.value)) {
        element.value = element.value.substring(0, element.value.length - 1)
    }
}

function maxValue(element, n) {
    if (element.value > n) {
        element.value = element.value.substring(0, element.value.length - 1)
    }
}


for (let i = 0; i < toDoElements.length; i++) {
    let inputs = toDoElements[i].getElementsByClassName("todo-checkbox");
    toDoElements[i].addEventListener("click", function (event) {
        for (let j = 0; j < inputs.length; j++) {
            if (event.target !== inputs[j]) {
                inputs[j].checked = !inputs[j].checked;
            }
        }
    });
}

document.getElementById("year").addEventListener("input", function (event) {
    maxCharacters(event.target, 4);

})

document.getElementById("month").addEventListener("input", function (event) {
    maxCharacters(event.target, 2);
    maxValue(event.target, 12);
})

document.getElementById("day").addEventListener("input", function (event) {
    maxCharacters(event.target, 2);
    maxValue(event.target, 31);
})

document.getElementById("hour").addEventListener("input", function (event) {
    maxCharacters(event.target, 2);
    maxValue(event.target, 23);
})

document.getElementById("minute").addEventListener("input", function (event) {
    maxCharacters(event.target, 2);
    maxValue(event.target, 59);
})

descriptionElement.addEventListener("input", function (event){
    adjustDescriptionSize(event.target);
});

window.addEventListener("load", function () {
    adjustDescriptionSize(descriptionElement);
});

