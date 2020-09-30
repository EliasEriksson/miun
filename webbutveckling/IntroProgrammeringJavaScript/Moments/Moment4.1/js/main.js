let output = document.getElementById("output");


function Movie(title, category, playTime) {
    this.title = title;
    this.category = category;
    this.playtime = playTime;
    this.getInformation = function() {
        return `${this.title}, ${this.category}, ${this.playtime} minuter`
    };
}

let movie1 = new Movie("Star Wars", "Science fiction", 104);
let movie2 = new Movie("Introduktion till JavaScript", "Utbildning", 24);
let movie3 = new Movie("Borta med vinden", "Drama", 210);
let movie4 = new Movie("Jagad", "Thriller", 105);

let movies = [
    movie1, movie2, movie3, movie4
]

console.log(movie1)


output.innerHTML = movies.map(movie => movie.getInformation()).join("\n");
