function foo(){
    let chars = [
        0x77, 0x65, 0x20, 0x61, 0x72, 0x65,
        0x20, 0x6c, 0x65, 0x61, 0x72, 0x6e,
        0x69, 0x6e, 0x67, 0x21];
    let s = "";
    for (let i = 0; i < chars.length; i++) {
        s += String.fromCodePoint(chars[i]);
    }
    return s;
}

result = foo();
console.log(result);
