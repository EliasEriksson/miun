let id = document.currentScript.getAttribute("userID");

let cluckLoader = new CluckLoader("getUserClucks", id);

window.addEventListener("load", async () => {
    await cluckLoader.loadClucks();
});

window.addEventListener("scroll", async () => {
    await cluckLoader.loadClucks();
});