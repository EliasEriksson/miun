import {getCookies} from "./modules/cookie.js";
import {requestEndpoint, requestTemplate} from "./modules/requests.js";
import {render} from "./modules/xrender.js";

const formElement = document.getElementById("course-form");
const courseIdElement = formElement.querySelector("[name=courseId]");
const courseNameElement = formElement.querySelector("[name=courseName]");
const coursePeriodElement = formElement.querySelector("[name=coursePeriod]");
const postElement = formElement.querySelector("[name=post-button]")
const tableBodyElement = document.getElementById("result-data");

const getCurrentFormCourse = () => {
    return {
        courseId: courseIdElement.value,
        courseName: courseNameElement.value,
        coursePeriod: coursePeriodElement.value
    }
}

const getAll = async () => {
    let cookies = getCookies();
    let templateP = requestTemplate(`${cookies["staticRoot"]}/templates/courseRow.html`);
    let coursesP = requestEndpoint(`/courses/`);

    let template = await templateP;
    let [courses, status] = await coursesP;

    for (const course of courses) {
        let spacer = document.createElement("div");
        spacer.classList.add("spacer");
        course["courseLink"] = `${cookies["rootURL"]}/course/${course.id}/`;
        tableBodyElement.appendChild(spacer);
        tableBodyElement.appendChild(render(template, course));
    }
}

const post = async () => {
    let [data, status] = await requestEndpoint(`/courses/`, null, "post", {
        ...getCurrentFormCourse()
    });
}

window.addEventListener("load", async () => {
    formElement.addEventListener("submit", e => {
        e.preventDefault();
    });

    postElement.addEventListener("click", async e => {
        e.preventDefault();
        await post();
        location.reload();
    });

    await getAll();
});