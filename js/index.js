var myVar = setInterval(function() {
    myTimer();
}, 1000);

function myTimer() {
    var d = new Date();
    document.querySelector(".header_clock").innerHTML = d.toLocaleTimeString();
}