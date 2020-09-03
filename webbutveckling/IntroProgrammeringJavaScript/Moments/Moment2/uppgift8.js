"use strict";

function long(array) {
    if (array){
        let best = 0;
        for (let i = 0; i < array.length; i++) {
            if (array[best].length < array[i].length) {
                best = i;
            }
        }
        return array[best];
    } else {
        console.log("Empty array.")
    }
}

console.log(long(["Örebro", "Sundsvall", "Hudiksvall", "Göteborg"]))
