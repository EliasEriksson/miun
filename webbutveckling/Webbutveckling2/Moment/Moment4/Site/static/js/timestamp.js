// format for time difference larger than 24h and the year have changed
let yearFormat = Intl.DateTimeFormat("sv", {
    year: "numeric", month: "short", day: "numeric"
});
// format for time where the difference is more than 24h but the year is still the same
let dayMonthFormat = Intl.DateTimeFormat("sv", {
    month: "short", day: "numeric"
});

/**
 * formats the time from unix time to the time that should be displayed
 *
 * @param timestamp
 * @returns {string}
 */
function timeAgo(timestamp) {
    let now = new Date();
    let timeDifference = Math.round(now.getTime() / 1000) - timestamp;

    if (timeDifference / 86400 > 1) {
        let then = new Date(timestamp * 1000);
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

/**
 * formats a single element to use the displayed time format instead of unix time
 *
 * @param element
 */
function formatTimeStamp(element) {
    let timestamp = parseInt(element.innerHTML, 10);
    element.innerHTML = timeAgo(timestamp);
}

/**
 * formats all elements on the page that have the class timestamp to use
 * the display time format instead of unix timestamp
 */
function formatTimestamps() {
    let timeStampElements = document.getElementsByClassName("timestamp");
    for (let i = 0; i < timeStampElements.length; i++) {
        formatTimeStamp(timeStampElements[i])
    }
}

/**
 * convert all timestamps as soon the page is loaded
 */
window.addEventListener("load", () => {
    formatTimestamps();
});