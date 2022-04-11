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

        <div id="calendarDIV">
            <?=$calendar?>
        </div>

        <script>
            let month_activities = <?=count($monthsActivities) == 0 ? 0 : json_encode($monthsActivities)?>;
        </script>
    </body>
</html>