"use strict";
let date = new Date();
let dateFormat = new Intl.DateTimeFormat("sv", {
    weekday: "long"
});
let timeFormat = new Intl.DateTimeFormat("sv", {
    hour: "numeric", minute: "numeric", second: "numeric"
});

function capitalize(string) {
    let letters = string.split("")
    letters[0] = letters[0].toUpperCase()
    string = letters.join("")
    return string

}

console.log(`Idag är det: ${capitalize(dateFormat.format(date))}`);
console.log(`Aktuell tid är: ${timeFormat.format(date)}`);
