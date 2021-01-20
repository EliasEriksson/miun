
$("#popular-glasses-slideshow > a:gt(0)").hide();

setInterval(function() {
    $(
        "#popular-glasses-slideshow > a:first"
    ).fadeOut(
        1500
    ).next(
    ).fadeIn(
        1500
    ).end(
    ).appendTo("#popular-glasses-slideshow");
}, 4000);