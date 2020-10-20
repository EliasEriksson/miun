"use strict";

let mainNavList = document.getElementById("mainnavlist");
let playChannel = document.getElementById("playchannel");
let radioPlayer = document.getElementById("radioplayer");
let playButton = document.getElementById("playbutton");
let numRows = document.getElementById("numrows");
let info = document.getElementById("info");

let api = "https://api.sr.se/api/v2/";

class Settings {
    constructor() {
        /**
         * constructs a Settings object by attempting to load from localstorage.
         * if there is no settings in localstorage the defaults are loaded and saved.
         */
        let settings = JSON.parse(localStorage.getItem("settings"));
        if (settings) {
            this.settings = settings;
        } else {
            this.settings = this.defaultSettings();
            this.saveUserSettings();
        }
    }

    defaultSettings(){
        /**
         * returns default settings.
         */
        return {
            "volume": 0.5,
            "numRows": 10
        };
    }

    getSetting(setting) {
        /**
         * method to get a value from a setting.
         */
        return this.settings[setting];
    }

    setSetting(setting, value) {
        /**
         * method to set a setting to a value.
         *
         * the settings are expediently saved to localstorage.
         */
        this.settings[setting] = value;
        this.saveUserSettings();
    }

    saveUserSettings() {
        /**
         * saves the current settings to localstorage.
         */
        localStorage.setItem("settings", JSON.stringify(this.settings));
    }
}

let settings = new Settings();

let timeFormat = new Intl.DateTimeFormat("sv", {
    hour: "numeric", minute: "numeric"
});

function streamAPI(id){
    /**
     * api URL for live stream of channel with id
     */
    return `https://sverigesradio.se/topsy/direkt/${id}.mp3`
}

function scheduleAPI(id){
    /**
     * api URL for today's shows for some channel
     *
     * size set to static 100 to get all the shows for today.
     * there should never be more than 100 shows.
     */
    return `${api}scheduledepisodes?channelid=${id}&size=100&format=json`
}

function channelsAPI(){
    /**
     * api URL for a list of all channels.
     */
    return `${api}channels?format=json&pagination=false`;
}


function formatTime(start, end) {
    /**
     * formats the start and end time given from API calls.
     *
     * end is already slightly formatted before this function call.
     */
    start = timeFormat.format(new Date(start.match(/(\d+)/)[0]-0));
    end = timeFormat.format(end);
    return `${start} - ${end}`;
}


function formatSubTitle(subtitle) {
    /**
     * cleans the sub title from : and extra whitespace.
     */
    return subtitle.replace(/^\s*:\s*/, "")
}


function createChannelOption(channel) {
    /**
     * creates a new option tag with a channel and the channels id as attribute.
     */
    let option = document.createElement("option");
    option.setAttribute("id", channel.id);
    option.innerHTML = channel.name;
    playChannel.appendChild(option);
}


function createChannelLi(channel) {
    /**
     * creates a new list element for the main navigation.
     *
     * no href is given to the a tag to mimic the style of provided material.
     * instead an event listener is set on the a tag to update today's program when clicked.
     */
    let li = document.createElement("li");
    let a = document.createElement("a");

    a.setAttribute("id", channel.id);
    a.addEventListener("click", requestChannelProgram);
    a.innerHTML = channel.name;
    a.title = channel.tagline;

    li.appendChild(a);
    mainNavList.appendChild(li);
}


function createProgram(program) {
    /**
     * creates a new article element to hold program data.
     *
     * end time is partially processed here to check if this specific program have ended already
     */
    let end = new Date(program.endtimeutc.match(/(\d+)/)[0] - 0);
    if (end > new Date()) {
        let article = document.createElement("article");
        let title = document.createElement("h3");
        let underTitle = document.createElement("h4");
        let time = document.createElement("h5");
        let description = document.createElement("p");

        title.innerHTML = program.title;
        underTitle.innerHTML = program.hasOwnProperty("subtitle") ? formatSubTitle(program.subtitle) : "";
        time.innerHTML = formatTime(program.starttimeutc, end);
        description.innerHTML = program.description;

        article.appendChild(title);
        article.appendChild(underTitle);
        article.appendChild(time);
        article.appendChild(description);
        info.appendChild(article);
    }
}


function playSelected() {
    /**
     * adds and plays the currently selected audio stream.
     *
     * attempts to use last used volume from settings otherwise half volume is used
     */

    let option = playChannel[playChannel.selectedIndex]

    radioPlayer.innerHTML = "";
    let audio = document.createElement("audio");
    let source = document.createElement("source");

    audio.setAttribute("controls", "");
    audio.setAttribute("autoplay", "true");
    audio.setAttribute("preload", "auto")
    audio.volume = settings.getSetting("volume");

    source.setAttribute("src", streamAPI(option.id));

    source.setAttribute("type", "audio/mpeg");
    audio.addEventListener("volumechange", function (event){
        settings.setSetting("volume", event.target.volume);
    });

    audio.appendChild(source);
    radioPlayer.appendChild(audio);
}


function requestChannelProgram(event){
    /**
     * requests the all the current programs from the clicked channel on the main navigation
     * and fills div#info with program data thru createProgram()
     *
     */
    event.preventDefault();
    info.innerHTML = "";
    let request = new XMLHttpRequest();
    request.addEventListener("load", function (){
        if (this.status === 200) {
            let response = JSON.parse(this.response);
            for (let i = 0; i < response.schedule.length; i++) {
                createProgram(response.schedule[i]);
            }
        }
    });
    request.open("get", scheduleAPI(event.target.id), true);
    request.send();
}


function requestChannels(size) {
    /**
     * requests 10 or given amount of channels to list in select#playchannel
     * and ul#mainnavlist
     */
    playChannel.innerHTML = ""
    mainNavList.innerHTML = ""
    let request = new XMLHttpRequest();
    request.addEventListener("load", function () {
        if (this.status === 200) {
            let response = JSON.parse(this.response);
            for (let i = 0; i < response.channels.length; i++) {
                createChannelOption(response.channels[i]);
                if (i < size) {
                    createChannelLi(response.channels[i]);
                }
            }
        }
    });

    request.open("get", channelsAPI(), true);
    request.send();
}

playButton.addEventListener("click", playSelected);

numRows.addEventListener("change", function (){
    /**
     *
     */
    settings.setSetting("numRows", numRows.value);
    requestChannels(numRows.value);
})

window.addEventListener("load", function (){
    /**
     *
     */

    numRows.value = settings.getSetting("numRows")
    requestChannels(settings.getSetting("numRows"));
});
