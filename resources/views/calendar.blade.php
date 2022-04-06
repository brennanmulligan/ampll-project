<?php
use App\Http\Helpers\Calendar;

$athlete_id = $_GET['athlete_id'] ?? 0;

if (isset($_GET["date"])) {
    $date = $_GET['date'];
} else {
    $date = date("Y-m-d");
}
$calendar = new Calendar($date);

$activityController = new \App\Http\Controllers\ActivityController();
$monthsActivities = $activityController->getMonthActivityData($athlete_id, $date);

$calendar->setActivitiesForCalendar($calendar, $monthsActivities);

if (isset($_GET["mode"])) {
    $arr[] = "";
    $arr[0] = $calendar;
    $arr[1] = $monthsActivities;

    echo implode(",,,", $arr);
    exit();
}

// Handle connectivity
if (isset($_GET["refresh"]) && $_GET["athlete_id"] != 0) {
    $gatewayController = new \App\Http\Controllers\GatewayController();

    if ($gatewayController->refreshData($_GET["athlete_id"]) == -1) {
        header('Location: /login');
        exit();
    }
}

$athleteController =  new \App\Http\Controllers\AthleteController();
$athlete = $athleteController->getAthlete($athlete_id);

?>
<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="{{ asset('css/calendar.css') }}" type="text/css">
    <link rel="stylesheet" href="{{ asset('css/interface.css') }}" type="text/css">
    <link rel="stylesheet" href="{{ asset('css/modal_box.css') }}" type="text/css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

    <title>User Calendar</title>

    <style>
        #test {
            font-size: 100px;
            color: white;
            display: none;
        }

        /* The switch - the box around the slider */
        .switch {
            position: relative;
            display: inline-block;
            width: 35px;
            height: 17px;
        }

        /* Hide default HTML checkbox */
        .switch input {
            opacity: 0;
            width: 0;
            height: 0;
        }

        /* The slider */
        .slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: #ccc;
            -webkit-transition: .4s;
            transition: .4s;
        }

        .slider:before {
            position: absolute;
            content: "";
            height: 13px;
            width: 13px;
            left: 2px;
            bottom: 2px;
            background-color: white;
            -webkit-transition: .4s;
            transition: .4s;
        }

        input:checked + .slider {
            background-color: #2196F3;
        }

        input:focus + .slider {
            box-shadow: 0 0 1px #2196F3;
        }

        input:checked + .slider:before {
            -webkit-transform: translateX(18px);
            -ms-transform: translateX(18px);
            transform: translateX(18px);
        }

        /* Rounded sliders */
        .slider.round {
            border-radius: 34px;
        }

        .slider.round:before {
            border-radius: 50%;
        }
    </style>
</head>
<body>s
    <a id="backLink" href="{{URL::to('/')}}"><< Back to Landing Page</a>
    <h1>Calendar</h1>
    <p> Welcome back, {{$athlete->first_name}} ({{$athlete_id}})</p>

    <div class="row">
        <div class="column">
            <a href='calendar?athlete_id=<?php echo $athlete_id ?>&refresh=Refresh'>
                <img src="img/btn_strava_connectwith_orange.svg" alt="Connect with Strava"/>
            </a>
        </div>
        <div class="column">
            <!-- Rounded switch -->
            <p>Make Private Activites Hidden</p>
            <label class="switch">
                <input id="hidePrivate" type="checkbox" onchange='privateActivitiesToggle(this);'>
                <span class="slider round"></span>
            </label>

            <p>Show Hidden Activities</p>
            <label class="switch">
                <input id="hideHidden" type="checkbox" onchange='hiddenActivitiesToggle(this);'>
                <span class="slider round"></span>
            </label>
        </div>
    </div>

    <div id="moreActivities">
        <div id="modal_content">
            <div id="modal-header">
                <button id="close" onclick="closeModal()">&times;</button>
                <h2 id="modal_h2"></h2>
            </div>
            <div id="activities_content"></div>
        </div>
    </div>

    <div id="calendarDIV">
        <?=$calendar?>
    </div>
</body>
</html>
<script>
    let month_activities = JSON.parse(<?=count($monthsActivities) == 0 ? 0 : json_encode($monthsActivities)?>);

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

        for (let rowCount = 0; rowCount < 4; rowCount++) {
            let row = table.insertRow();

            for (let i = 0; i < 2; i++) {
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
                response = response.split(",,,");

                month_activities = JSON.parse(response[1]);
                document.getElementById("calendarDIV").innerHTML = response[0];
            }
        });
    }

    function privateActivitiesToggle(e) {
        for(let i = 0; i < month_activities.length; i++) {

            // If the activity is private
            if(month_activities[i]["private"] === 1) {
                let element = document.getElementById(month_activities[i]['activity_id']);

                // Hide or unhide accordingly
                element.hidden = e.checked;
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
</script>