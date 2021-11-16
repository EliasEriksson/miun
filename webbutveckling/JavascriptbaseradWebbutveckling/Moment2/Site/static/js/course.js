import {requestEndpoint} from "./modules/requests.js";
import {redirect} from "./modules/url.js";

const form = document.getElementById("course-form");
const courseIdElement = form.querySelector("[name=courseId]");
const courseNameElement = form.querySelector("[name=courseName]");
const coursePeriodElement = form.querySelector("[name=coursePeriod]");
const putElement = form.querySelector("[name=update-button]");
const delElement = form.querySelector("[name=delete-button]");


const getIdFromURL = () => {
    let parts = location.href.split("/");
    if (parts[parts.length - 1]) {
        return parseInt(parts[parts.length - 1]);
    }
    return parseInt(parts[parts.length - 2]);
}

const getCurrentFormCourse = () => {
    return {
        courseId: courseIdElement.value,
        courseName: courseNameElement.value,
        coursePeriod: coursePeriodElement.value
    }
}

const put = async () => {
    let id = getIdFromURL();
    let [data, status] = await requestEndpoint(`/course/${id}/`, null, "put", {
        ...getCurrentFormCourse()
    });
    console.log("updated to:", data);
}

const del = async () => {
    let id = getIdFromURL();
    let [data, status] = await requestEndpoint(`/course/${id}/`, null, "delete");
    console.log("deleted:", data);
}

window.addEventListener("load", async () => {
    form.addEventListener("submit", async e => {
        e.preventDefault();
    });

    putElement.addEventListener("click", async e => {
        e.preventDefault();
        await put();
        redirect("../../");
    });

    delElement.addEventListener("click", async e => {
        e.preventDefault();
        await del();
        redirect("../../");
    });

    fetch("asdasd").then((response) => {

    })
});