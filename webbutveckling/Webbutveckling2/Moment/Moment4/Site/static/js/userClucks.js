/**
 * loads clucks from /api/getUserClucks/
 * when the page is loaded and if the user scroll
 */
let cluckLoader = new CluckLoader(
    "getUserClucks",
    document.currentScript.getAttribute("data-root"),
    document.currentScript.getAttribute("data-writeLink"),
    document.currentScript.getAttribute("userID")
);

window.addEventListener("load", async () => {
    await cluckLoader.loadClucks();
});

window.addEventListener("scroll", async () => {
    await cluckLoader.loadClucks();
});