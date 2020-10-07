"use strict";

let movie1 = {
    title: "Marmeladupproret",
    category: "Komedi",
    playtime: 88,
    getInformation: function () {
        return this.title + ", " + this.category + ", " + this.playtime + " minuter";
    }
},

 movie2 = {
    title: "Den andra systern Boleyn",
    category: "Drama",
    playtime: 111,
    getInformation: function () {
        return this.title + ", " + this.category + ", " + this.playtime + " minuter";
    }
},

movie3 = {
    title: "Korsdrag i paradiset",
    category: "Romantisk komedi",
    playtime: 93,
    getInformation: function () {
        return this.title + ", " + this.category + ", " + this.playtime + " minuter";
    }
};

let movie4 = {
    title: "Cats",
    category: "Musikal",
    playtime: 110,
    getInformation: function () {
        return this.title + ", " + this.category + ", " + this.playtime + " minuter";
    }
};


let output = (document.getElementById("output"));

output.innerHTML += (movie1.getInformation()) + "\n" +
                    (movie2.getInformation()) + "\n" +
                    (movie3.getInformation()) + "\n" +
                    (movie4.getInformation());