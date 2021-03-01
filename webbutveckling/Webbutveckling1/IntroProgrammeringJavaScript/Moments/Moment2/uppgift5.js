"use strict";
function multiTable(number) {
    /**
     * takes a number and multiplies it with 1-10 inclusive
     */
    for (let i = 1; i < 11; i++) {
        console.log(`${number} * ${i} = ${number * i}`);
    }
}

multiTable(5);
