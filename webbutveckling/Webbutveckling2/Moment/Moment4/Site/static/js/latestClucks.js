/**
 * loads clucks from /api/getLatestClucks/
 * when the page is loaded and if the user scroll
 */

let cluckLoader = new CluckLoader(
    "getLatestClucks",
    document.currentScript.getAttribute("data-root"),
    document.currentScript.getAttribute("data-writeLink")
);

window.addEventListener("load", async () => {
    await cluckLoader.loadClucks();
});

window.addEventListener("scroll", async () => {
    await cluckLoader.loadClucks();
});