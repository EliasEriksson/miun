let output = document.getElementById("output");

function newMovie(title, category, playtime){
    return {
        "title": title,
        "category": category,
        "playtime": playtime,
        "getInformation": function() {
            return `${this.title}, ${this.category}, ${this.playtime} minuter.`
        }
    };
}

let movie1 = newMovie("Star Wars", "Science fiction", 104);
let movie2 = newMovie("Introduktion till JavaScript", "Utbildning", 24);
let movie3 = newMovie("Borta med vinden", "Drama", 210);
let movie4 = newMovie("Jagad", "Thriller", 105);

let movies = [
    movie1, movie2, movie3, movie4
]

output.innerHTML += movies.map(movie => movie.getInformation()).join("\n");
