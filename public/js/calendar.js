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

function updateCalendar(date, action) {
    let url = window.location.href + "&" + action + "&date=" + date;

    fetch (url, {
        method: "GET",
    })
    .then(response => response.text())
    .then(function(data) {
        data = data.split(",,,");

        let calendar = document.getElementById("calendar");
        let table = document.createElement("table");
        table.innerHTML = data[0];

        let tableRows = table.rows;
        let calendarRows = 2;

        if (tableRows.length > 5) {
            calendar.appendChild(document.createElement("tr"));
        } else if (tableRows.length === 5 && calendar.rows.length === 8) {
            calendar.deleteRow(calendar.rows.length - 1);
        }

        for (let i = 0; i < tableRows.length; i++) {
            calendar.rows[calendarRows].innerHTML = tableRows[i].innerHTML;
            calendarRows++;
        }

        document.getElementById("header").innerHTML = getMonthAndYear(date);

        month_activities = JSON.parse(data[1]);
    })
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
        } else {
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
    let tempDate = date.split("-");

    return getMonth("getName", parseInt(tempDate[1])) + " " + tempDate[2] + ", " + tempDate[0];
}

function getMonth(type, month) {
    let monthsArr = {
        1: "January",       7: "July",
        2: "February",      8:"August",
        3: "March",         9: "September",
        4: "April",         10: "October",
        5: "May",           11: "November",
        6: "June",          12: "December"
    }

    if (type === "getName") {
        return monthsArr[month];
    } else if (type === "getNumber") {
        return parseInt(Object.keys(monthsArr).find(key => monthsArr[key] === month));
    }
}

function getNewDate(goForward) {
    let header = document.getElementById("header");
    let month = getMonth("getNumber", header.innerHTML.substring(0, header.innerHTML.indexOf(" ")));
    let year = parseInt(header.innerHTML.substring(header.innerHTML.indexOf(" ") + 1));
    // Default for day is 01. This will change if we're switching to the current month and year
    let day = "01";

    let padWithZero = (num) => {
        return (num).toString().padStart(2, "0");
    }

    if (goForward === false) {
        if (month === 1) {
            month = 12;
            year--;
        } else {
            month = padWithZero(month-1);
        }
    } else {
        if (month === 12) {
            month = padWithZero(1);
            year++;
        } else {
            month = padWithZero(month+1);
        }
    }

    let today = new Date();
    if (parseInt(month) === (today.getMonth()+1) && year === today.getFullYear()) {
        day = padWithZero(today.getDate());
    }

    return year.toString() + "-" + month + "-" + day;
}

function getMonthAndYear(date) {
    let dateMonth = parseInt(date.substring(date.indexOf("-")+1, 7));
    let dateYear = parseInt(date.substring(0, date.indexOf("-")));

    return getMonth("getName", dateMonth) + " " + dateYear;
}

function hideActivity(activity) {
    let header = document.getElementById("header");
    let month = getMonth("getNumber", header.innerHTML.substring(0, header.innerHTML.indexOf(" ")));
    let year = parseInt(header.innerHTML.substring(header.innerHTML.indexOf(" ") + 1));
    let tempMonth = month < 10 ? "0" + month.toString() : month.toString();
    let date = year.toString() + "-" + tempMonth + "-" + "01";

    updateCalendar(date, "activity=" + activity.activity_id);
    /*$.ajax({
        url: window.location.href,
        type: "GET",
        data: {activity: activity.activity_id, date: date}, // Send to the server
        success: function(response) {
            ajaxRedraw(response);
        }
    });*/

}

/*function ajaxRedraw(response) {
    response = response.split(",,,");

    // Updates month_activities for the new calendar
    month_activities = JSON.parse(response[1]);

    // Redraw the calendar
    document.getElementById("calendarDIV").innerHTML = response[0];
}*/

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