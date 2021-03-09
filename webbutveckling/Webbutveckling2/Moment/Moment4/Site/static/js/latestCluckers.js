let cluckerLoader = new CluckerLoader("getLatestUsers");

window.addEventListener("load", async ()=> {
    await cluckerLoader.loadUsers();
});

window.addEventListener("scroll", async ()=> {
    await cluckerLoader.loadUsers();
});
