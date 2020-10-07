let content = document.getElementById("content");

function clearBody(){
    while (content.firstChild) {
        content.removeChild(content.firstChild);
    }
}

function writeImage(imageURL){
    clearBody();
    console.log(imageURL)
    let img = document.createElement("img");
    img.setAttribute("alt", "image of dog");
    img.setAttribute("src", imageURL);
    content.appendChild(img);
}

async function requestImage(){
    let response = await fetch("https://dog.ceo/api/breed/eskimo/images/random");
    let json = await response.json();
    return json.message;
}

window.addEventListener("load", async function (){
    let imageURL = await requestImage();
    writeImage(imageURL);
});
