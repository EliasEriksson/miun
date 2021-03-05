let yearFormat = Intl.DateTimeFormat("sv", {
    year: "numeric", month: "short", day: "numeric"
})

let dayMonthFormat = Intl.DateTimeFormat("sv", {
    month: "short", day: "numeric"
})

function timeAgo(timestamp) {
    let now = new Date();
    let timeDifference = Math.round(now.getTime() / 1000) - timestamp;

    if (timeDifference / 86500 > 1) {
        let then = new Date(timestamp);
        if (then.getFullYear() === now.getFullYear()) {
            return dayMonthFormat.format(then);
        } else {
            return yearFormat.format(then);
        }
    }

    if (timeDifference / 3600 > 1) {
        return Math.round(timeDifference / 3600) + "h";
    }

    if (timeDifference / 60 > 1) {
        return Math.round(timeDifference / 60) + "m";
    }

    return timeDifference + "s";
}

function formatTimeStamp(element) {
    let timestamp = parseInt(element.innerHTML, 10);
    element.innerHTML = timeAgo(timestamp);
}

function formatTimestamps() {
    let timeStampElements = document.getElementsByClassName("timestamp");
    for (let i = 0; i < timeStampElements.length; i++) {
        formatTimeStamp(timeStampElements[i])
    }
}

window.addEventListener("load", () => {
    formatTimestamps();
});