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
     * builds a pyramid with width `width` (technically height but its sideways...)
     *
     * this is done by starting to loop from a negative number and passing 0 to a positive number.
     * using Math.abs() turns the raising value of the loop variable to get a high -> low -> high
     * pattern instead. subtracting this high -> low -> high sequence from the parameter width gives a
     * low -> high -> low sequence instead. this sequence is passed into `stars()` to log a pyramid in console.
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
