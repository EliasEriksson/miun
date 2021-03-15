let cluckLoader = new CluckLoader(
    "getTopClucks",
    document.currentScript.getAttribute("data-root"),
    document.currentScript.getAttribute("data-writeLink")
);

window.addEventListener("load", async () => {
    await cluckLoader.loadClucks();
});

window.addEventListener("scroll", async () => {
    await cluckLoader.loadClucks();
});