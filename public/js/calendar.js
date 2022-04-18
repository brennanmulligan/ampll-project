function showMoreActivities(elem, date) {
    let modal = document.getElementById("moreActivities");
    let content = document.getElementById("activities_content");
    let day_activities = [];

    document.getElementById("modal_h2").innerHTML = convertDate(date);

    let hidingPrivate = document.getElementById("hidePrivate").checked;

    if (elem.classList.contains("expand")) {
        for (let i = 0; i < month_activities.length; i++) {
            if (month_activities[i].start_date.substring(0, 10) === date) {
                if(!hidingPrivate || month_activities[i].private !== 1)
                    day_activities.push(month_activities[i]);
            }
        }
    } else if (elem.classList.contains("event")) {
        let found = false;
        let i = 0;

        while (!found && i < month_activities.length) {
            if (month_activities[i].activity_id === parseInt(elem.getAttribute("id"))) {
                day_activities.push(month_activities[i]);
                found = true;
            }

            i++;
        }
    }

    for (let i = 0; i < day_activities.length; i++) {
        let focus_panel = document.createElement("div");
        focus_panel.className = "focus_panel";

        focus_panel.appendChild(createTableFromData(day_activities[i]));
        content.appendChild(focus_panel);
    }

    modal.classList.remove("fadeOut");
    modal.classList.add("fadeIn");
}

function createTableFromData(activity) {
    let table = document.createElement("table");
    let fields = ["Name", "Type", "Time", "Distance", "Elevation", "Kudos", "Private (Strava)", "Hidden (Ampll)"];
    let IDs = ["foc_title", "foc_type",  "foc_time", "foc_dist", "foc_elev", "foc_kudos", "foc_private", "foc_hidden"];
    let arrKeys = ["name", "type", "elapsed_time", "distance", "total_elevation_gain", "kudos_count", "private", "is_hidden"];
    let locationInArray = 0;

    let numRows = 5;
    let numCols = 2;

    for (let rowCount = 0; rowCount < numRows; rowCount++) {
        let row = table.insertRow();

        // Manually add the Hide button
        if(rowCount === numRows - 1) {
            // Add a cell for the show / hide button
            let td1 = row.insertCell();

            let btn = document.createElement("input");
            btn.type = "button";
            btn.id = "hideButton";
            btn.value = "Toggle Hidden";
            btn.onclick = function() {hideActivity(activity)}; // Assign anonymous function to onclick
            td1.appendChild(btn);

            //Add an extra column just for alignment
            row.insertCell();

            // Add a cell for the Strava link
            let td2 = row.insertCell();

            let homeLink = document.createElement("a");
            homeLink.href = "https://www.strava.com/activities/" + activity.activity_id;
            homeLink.target = "_blank";

            let linkImage = document.createElement("img");
            linkImage.src = "img/ViewOnStrava.jpg";
            linkImage.alt = "View On Strava";
            linkImage.height = 30;
            homeLink.appendChild(linkImage);
            td2.appendChild(homeLink);

            continue;
        }

        for (let i = 0; i < numCols; i++) {
            let td1 = row.insertCell();
            td1.className = "focus";
            td1.innerHTML = fields[locationInArray] + ": ";

            let td2 = row.insertCell();
            td2.className = "info";
            td2.id = IDs[locationInArray];
            td2.innerHTML = activity[arrKeys[locationInArray]];

            locationInArray++;
        }
    }

    return table;
}

function closeModal() {
    let modal = document.getElementById("moreActivities");

    modal.classList.remove("fadeIn");
    modal.classList.add("fadeOut");

    /* Wait until animation finishes before clearing content */
    setTimeout(function() {
        document.getElementById("activities_content").innerHTML = "";
    }, 250);
}

window.onclick = function(event) {
    let modal = document.getElementById("moreActivities");

    if (event.target === modal) {
        closeModal();
    }
}

function convertDate(date) {
    let months = {
        1: "January",       7: "July",
        2: "February",      8:"August",
        3: "March",         9: "September",
        4: "April",         10: "October",
        5: "May",           11: "November",
        6: "June",          12: "December"
    }
    let tempDate = date.split("-");

    return months[parseInt(tempDate[1])] + " " + tempDate[2] + ", " + tempDate[0];
}

function changeMonth(goForward) {
    let months = {
        "January": 1,       "July": 7,
        "February": 2,      "August": 8,
        "March": 3,         "September": 9,
        "April": 4,         "October": 10,
        "May": 5,           "November": 11,
        "June": 6,          "December": 12
    }
    let header = document.getElementById("header");
    let month = months[header.innerHTML.substring(0, header.innerHTML.indexOf(" "))];
    let year = parseInt(header.innerHTML.substring(header.innerHTML.indexOf(" ") + 1));
    // Default for day is 01. This is change if we're switching to the current month and year
    let day = "01";

    if (goForward === false) {
        if (month === 1) {
            month = 12;
            year--;
        } else {
            month--;
        }
    } else {
        if (month === 12) {
            month = 1;
            year++;
        } else {
            month++;
        }
    }

    let today = new Date();
    if (month === (today.getMonth()+1) && year === today.getFullYear()) {
        day = today.getDate() < 10 ? "0" + today.getDate().toString() : today.getDate().toString();
    }

    let tempMonth = month < 10 ? "0" + month.toString() : month.toString();
    let date = year.toString() + "-" + tempMonth + "-" + day;

    $.ajax({
        url: window.location.href,
        type: "GET",
        data: {mode: "refreshCalendar", date: date},
        success: function(response) {
            ajaxRedraw(response);
        }
    });
}

function hideActivity(activity) {
    // TODO migrate this into a function so we don't have two of the same block of code
    let months = {
        "January": 1,       "July": 7,
        "February": 2,      "August": 8,
        "March": 3,         "September": 9,
        "April": 4,         "October": 10,
        "May": 5,           "November": 11,
        "June": 6,          "December": 12
    }
    let header = document.getElementById("header");
    let month = months[header.innerHTML.substring(0, header.innerHTML.indexOf(" "))];
    let year = parseInt(header.innerHTML.substring(header.innerHTML.indexOf(" ") + 1));
    let tempMonth = month < 10 ? "0" + month.toString() : month.toString();
    let date = year.toString() + "-" + tempMonth + "-" + "01";

    $.ajax({
        url: window.location.href,
        type: "GET",
        data: {activity: activity.activity_id, date: date}, // Send to the server
        success: function(response) {
            ajaxRedraw(response);
        }
    });
}

function ajaxRedraw(response) {
    response = response.split(",,,");

    // Updates month_activities for the new calendar
    month_activities = JSON.parse(response[1]);

    // Redraw the calendar
    document.getElementById("calendarDIV").innerHTML = response[0];
}

function privateActivitiesToggle(e) {
    for(let i = 0; i < month_activities.length; i++) {

        // If the activity is private
        if(month_activities[i]["private"] === 1) {
            let element = document.getElementById(month_activities[i]['activity_id']);

            // Hide or unhide accordingly
            element.hidden = !e.checked;
        }
    }
}

function hiddenActivitiesToggle(e) {
    for(let i = 0; i < month_activities.length; i++) {

        // If the activity is hidden from Ampll
        if(month_activities[i]["is_hidden"] === 1) {
            let element = document.getElementById(month_activities[i]['activity_id']);

            // Hide or unhide accordingly
            element.hidden = !e.checked;
        }
    }
}