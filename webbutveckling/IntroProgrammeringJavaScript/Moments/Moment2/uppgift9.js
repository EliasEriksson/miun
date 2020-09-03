function stars(number) {
    /**
     * logs `number amount of `space separated `*`.
     *
     * this is done by repeating the string `* ` by number amount
     * and trimming of the ending space.
     */
    console.log("* ".repeat(number).trim());
}

function pyramid(width) {
    /**
     * builds a pyramid with width `width`
     *
     * this is done by starting to loop from a negative number
     * and passing 0 in combination with abs() in combination with subtracting that from the width
     * to create a raising -> falling number sequence that is then passed into `stars()`
     */
    if (width > 0) {
        for (let i = -width + 1; i < width; i++) {
            stars(width - Math.abs(i))
        }
    } else {
        console.log(`width must be larger than 0`);
    }
}

pyramid(5);
