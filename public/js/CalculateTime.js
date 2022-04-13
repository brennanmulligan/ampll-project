let time = document.getElementById("time");

time.innerHTML = "<h1 style='margin: 0'>" + getCurrentTime() + "</h1>";

setInterval(function() {
    time.innerHTML = "<h1 style='margin: 0'>" + getCurrentTime() + "</h1>";

    //currentTime = currentTime.substring(0, currentTime.length - 3);
}, 15000);

/**
 * Get the current time
 *
 * @return the current time as a string
 */
function getCurrentTime() {
    let d = new Date();
    let hr = d.getHours() > 12 ? d.getHours() - 12 : d.getHours();
    let min = d.getMinutes();
    let ampm = d.getHours() > 11 ? " PM" : " AM";

    min = min < 10 ? "0" + min : min;

    return hr + ":" + min + ampm;
}