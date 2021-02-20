/**
 * applies the timezone client side with javascript so the user always gets the correct time
 * this allows the user to get the correct time shown even if they are connecting thru a VPN
 * that is located in another timezone but forces swedish date format.
 */

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