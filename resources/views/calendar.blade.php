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

// If activity is set, grab the id, otherwise error to -1
$sentActivity = $_GET["activity"] ?? -1;
// Call to update DB
$sentActivity = $activityController->toggleActivityHidden($sentActivity);

$monthsActivities = $activityController->getMonthActivityData($athlete_id, $date);

$calendar->setActivitiesForCalendar($calendar, $monthsActivities);

if (isset($_GET["mode"]) || isset($_GET["activity"])) {
    // Load an array with the new calendar and activities
    $arr[] = "";
    $arr[0] = $calendar;
    $arr[1] = $monthsActivities;

    // Send things back to ajax for redraw
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
        <script src="{{ asset('js/calendar.js') }}" defer></script>
        <script src="{{ asset('js/CalculateTime.js') }}" defer></script>

        <title>User Calendar</title>
    </head>
    <body>
        <a id="backLink" href="{{URL::to('/')}}"><< Back to Landing Page</a>
        <h1>Calendar</h1>
        <p> Welcome back, {{$athlete->first_name}} ({{$athlete_id}})</p>

        <table id="tools">
            <tbody>
            <tr>
                <td>
                    <a href='calendar?athlete_id=<?php echo $athlete_id ?>&refresh=Refresh'>
                        <img src="img/btn_strava_connectwith_orange.svg" alt="Connect with Strava"/>
                    </a>
                </td>
                <td>
                    <p>Show Strava's Private Activities</p>
                    <label class="switch">
                        <input id="hidePrivate" type="checkbox" checked="true" onchange='privateActivitiesToggle(this);'>
                        <span class="slider round"></span>
                    </label>
                </td>
            </tr>
            <tr>
                <td id="time"></td>
                <td>
                    <p>Show Hidden Activities</p>
                    <label class="switch">
                        <input id="hideHidden" type="checkbox" checked="true" onchange='hiddenActivitiesToggle(this);'>
                        <span class="slider round"></span>
                    </label>
                </td>
            </tr>
            </tbody>
        </table>

        <div id="moreActivities">
            <div id="modal_content">
                <div id="modal-header">
                    <button id="close" onclick="closeModal()">&times;</button>
                    <h2 id="modal_h2"></h2>
                </div>
                <div id="activities_content"></div>
            </div>
        </div>
<<<<<<< HEAD
=======
    </div>

    <div id="calendarDIV">
        <?=$calendar?>
    </div>
</body>
</html>
<script>
    console.log("pre: {{$monthsActivities}}");
    let month_activities = <?=count($monthsActivities) == 0 ? 0 : json_encode($monthsActivities)?>;
    console.log("M_A: "+month_activities);

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
                homeLink.innerHTML = "View on Strava";
                td2.appendChild(homeLink);

                continue;
            }

            for (let i = 0; i < numCols; i++) {
                let td1 = row.insertCell();
                td1.className = "focus";
                td1.innerHTML = fields[locationInArray] + ": ";
>>>>>>> 33dd112eb04a91a02e2429e8326b64daddda4668

        <div id="calendarDIV">
            <?=$calendar?>
        </div>

        <script>
            let month_activities = <?=count($monthsActivities) == 0 ? 0 : json_encode($monthsActivities)?>;
        </script>
    </body>
</html>