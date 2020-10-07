"use strict";
let date = new Date();

// a time format for current day
let dateFormat = new Intl.DateTimeFormat("sv", {
    weekday: "long"
});

// a time format for current time
let timeFormat = new Intl.DateTimeFormat("sv", {
    hour: "numeric", minute: "numeric", second: "numeric"
});

function capitalize(string) {
    /**
     * capitalizes the first character in a string and lowercases the rest
     */
    if (string) {
        if (string.length === 1) {
            return string[0].toUpperCase();
        } else {
            return string[0].toUpperCase() + string.slice(1).toLowerCase();
        }
    }
    console.log("empty string.\nreturning the empty string.")
    return string;

}
// logs the current day
console.log(`Idag är det: ${capitalize(dateFormat.format(date))}`);
// logs the current time
console.log(`Aktuell tid är: ${timeFormat.format(date)}`);
