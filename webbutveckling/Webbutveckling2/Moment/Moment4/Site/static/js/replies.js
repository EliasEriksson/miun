let id = document.currentScript.getAttribute("cluckID");

let cluckLoader = new CluckLoader("getReplies", id);

window.addEventListener("load", async ()=>{
    await cluckLoader.loadClucks();
    let replied = document.getElementById("replied");
    if (replied) {
        makeClickable(replied, replied.getAttribute("data-replied-url"));
    }
});

window.addEventListener("scroll", async ()=> {
    await cluckLoader.loadClucks();
});