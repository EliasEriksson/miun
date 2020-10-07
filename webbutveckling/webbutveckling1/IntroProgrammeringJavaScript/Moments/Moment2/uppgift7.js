"use strict";
let number = 91;

// devices the number with 60 and throws away the decimals.
let hours = Math.trunc(91 / 60);

// the remainder
let minutes = number % 60;

console.log(`${hours} timmar och ${minutes}`);
