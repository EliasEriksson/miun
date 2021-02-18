// javascript to convert stored UTC time to the users timezone

let articleTimeElements = document.getElementsByClassName("article-time");
let timeFormat = new Intl.DateTimeFormat("sv", {
    year: "numeric", month: "numeric", day: "numeric",
    hour: "numeric", minute: "numeric", second: "numeric"
});


for (let i = 0; i < articleTimeElements.length; i++) {
    articleTimeElements[i].innerHTML = timeFormat.format(
        new Date(articleTimeElements[i].innerHTML * 1000)
    )
}