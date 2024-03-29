/**
 * loads clucks from /api/getReplies/
 * when the page is loaded and if the user scroll
 */
console.log(document.currentScript.getAttribute("data-cluckID"))
let cluckLoader = new CluckLoader(
    "getReplies",
    document.currentScript.getAttribute("data-root"),
    document.currentScript.getAttribute("data-writeLink"),
    document.currentScript.getAttribute("data-cluckID")
);

window.addEventListener("load", async ()=>{
    await cluckLoader.loadClucks();
    let replied = document.getElementById("replied");
    if (replied) {
        makeClickable(replied, `./?${replied.getAttribute("data-replied-url")}`);
    }
});

window.addEventListener("scroll", async ()=> {
    await cluckLoader.loadClucks();
});