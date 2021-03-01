"use strict";

function long(array) {
    /**
     * a function that finds and returns the longest string in an array.
     *
     * this is done by looping over the array and saving the "best" string
     * and comparing the best strings length with the next item in the array.
     * if the next string is longer store that string in the array.
     * after the end of the loop return the best string.
     *
     * if an empty array is given return nothing nad log "Empty array."
     */
    if (array){
        let best = 0;
        for (let i = 0; i < array.length; i++) {
            if (array[best].length < array[i].length) {
                best = i;
            }
        }
        return array[best];
    } else {
        console.log("Empty array.");
    }
}

console.log(long(["Örebro", "Sundsvall", "Hudiksvall", "Göteborg"]))
