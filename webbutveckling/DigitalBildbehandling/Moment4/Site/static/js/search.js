// assumes the user to be on a link ending with ?SEARCH_PHRASE_HERE

class Product {
    constructor(id, price, thumbnail, thumbnailAltText) {
        this.id = id;
        this.price = price;
        this.thumbnail = thumbnail;
        this.thumbnailAltText = thumbnailAltText;
    }
}

let products = {
    "Rakt Champagneglas": new Product("c0b5c33c", 129, "../media/glass.jpg"),
    "Litet kupat Dricksglas": new Product("d4d35b3e", 29, "../media/glass.jpg"),
    "Kupat Dricksglas": new Product("d7000c2e", 39, "../media/glass.jpg"),
    "Rakt Dricksglas": new Product("dd4bd193", 39, "../media/glass.jpg"),
    "Kupat Vinglas": new Product("v74f32ec", 89, "../media/glass.jpg"),
    "Vinglas": new Product("vf380160", 79, "../media/glass.jpg")
}

function removeFileExtension(path) {
    let lastIndex = path.lastIndexOf(".");
    return path.substring(0, lastIndex);
}

function generateSource(product, extension) {
    let sourceElement = document.createElement("source");

    sourceElement.setAttribute("srcset", `${removeFileExtension(product.thumbnail)}.${extension}`);
    sourceElement.setAttribute("type", `image/${extension}`);
    return sourceElement;
}

function generateImg(product) {
    let imgElement = document.createElement("img");
    imgElement.setAttribute("src", product.thumbnail);
    imgElement.setAttribute("alt", product.thumbnailAltText);
    return imgElement;
}

function generateThumbnail(product) {
    let pictureElement = document.createElement("picture");
    pictureElement.appendChild(generateSource(product, "avif"));
    pictureElement.appendChild(generateSource(product, "webp"));
    pictureElement.appendChild(generateImg(product));
    return pictureElement
}

function generateProductInfo(productName) {
    let pElement = document.createElement("p");
    pElement.innerHTML = productName;
    pElement.classList.add("card-product-info");
    return pElement;
}

function generatePrice(product) {
    let pElement = document.createElement("p");
    pElement.innerHTML = product.price + " :-";
    pElement.classList.add("card-product-price");
    return pElement;
}

function addSearchResult(productName, product, searchResultElement) {
    let productWrapperElement = document.createElement("div");
    productWrapperElement.classList.add("product-wrapper");

    let productElement = document.createElement("a");
    productElement.setAttribute("href", `../Glas/${product.id}/`)
    productElement.classList.add("product");

    productElement.appendChild(generateThumbnail(product));
    productElement.appendChild(generateProductInfo(productName));
    productElement.appendChild(generatePrice(product));

    productWrapperElement.appendChild(productElement);
    searchResultElement.appendChild(productWrapperElement);
}

window.addEventListener("load", function () {
    let searchResultElement = document.getElementById("search-result");
    let search = decodeURIComponent(window.location.href.split("?")[1]);
    for (let [name, product] of Object.entries(products)) {
        if (name.toLowerCase().includes(search)) {
            addSearchResult(name, product, searchResultElement);
        }
    }
});