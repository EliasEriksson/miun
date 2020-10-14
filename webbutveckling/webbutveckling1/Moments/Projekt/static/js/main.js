// let choice = 2;



function choose(choice){
    if (choice === 1) {
        console.log("Mic check...");
        choose(2);
    } else if (choice === 2){
        console.log("Hello world!");
        choose(3);
    } else if (choice === 3) {
        console.log("...and mars");
    } else {
        console.log("invalid choice");
    }
}

function c() {
    console.log("...and mars too");
}

function b() {
    console.log("Hello world!");
    c();
}

// function a () {
//     console.log("Mic check...")
//     b();
// }
//
// if (choice === 1) {
//     a();
// } else if (choice === 2) {
//     b();
// } else if (choice === 3) {
//     c();
// } else {
//     console.log("invalid choice")
// }
//
// if (choice === 1) {
//     console.log("Mic check...");
// }

choose(2);