let responses = document.getElementById("responses");

let request = new XMLHttpRequest();
request.addEventListener("readystatechange",  function () {
    console.log(this);
    if (this.readyState === 4) {
        console.log(this.response);
        responses.innerHTML = this.response;
    }
})

request.open("get", "data.txt", true);
request.send();
