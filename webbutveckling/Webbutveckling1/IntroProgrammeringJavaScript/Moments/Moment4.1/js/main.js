let output = document.getElementById("output");


function Movie(title, category, playTime) {
    /**
     * defines a movie object.
     */
    this.title = title;
    this.category = category;
    this.playtime = playTime;
    this.getInformation = function() {
        /**
         * returns a string representation of the movie object.
         */
        return `${this.title}, ${this.category}, ${this.playtime} minuter`
    };
}

// defines the movies as per request in the assignment
let movie1 = new Movie("Star Wars", "Science fiction", 104);
let movie2 = new Movie("Introduktion till JavaScript", "Utbildning", 24);
let movie3 = new Movie("Borta med vinden", "Drama", 210);
let movie4 = new Movie("Jagad", "Thriller", 105);

// stores them in a more usable format
let movies = [
    movie1, movie2, movie3, movie4
]

// iterates ver all movies in the array and gets their information then joins them together with a new line
output.innerHTML = movies.map(movie => movie.getInformation()).join("\n");
