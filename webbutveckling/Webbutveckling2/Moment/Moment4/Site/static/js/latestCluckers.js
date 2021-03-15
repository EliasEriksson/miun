let cluckerLoader = new CluckerLoader(
    "getLatestUsers",
    document.currentScript.getAttribute("data-root"),
    document.currentScript.getAttribute("data-writeLink")
);

window.addEventListener("load", async ()=> {
    await cluckerLoader.loadUsers();
});

window.addEventListener("scroll", async ()=> {
    await cluckerLoader.loadUsers();
});