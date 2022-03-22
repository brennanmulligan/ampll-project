<?php
use App\Http\Helpers\Calendar;

$athlete_id = $_GET['athlete_id'] ?? 0;

if (isset($_GET["date"])) {
    $date = $_GET['date'];
} else {
    $date = date("Y-m-d");
}
$calendar = new Calendar($date);

$activity_controller = new \App\Http\Controllers\ActivityController();
$monthsActivities = $activity_controller->getMonthActivityData($athlete_id, $date);

foreach($monthsActivities as $activity) {
    if($activity->type == "Ride" || $activity->type == "EBikeRide" || $activity->type == "Handcycle" || $activity->type == "Wheelchair" || $activity->type == "Velomobile" || $activity->type == "VirtualRide")
        // Ride-class events are displayed as red
        $color = "red";
    else if($activity->type == "Run" || $activity->type == "VirtualRun" || $activity->type == "Walk" || $activity->type == "Hike" || $activity->type == "Elliptical" || $activity->type == "StairStepper")
        // Run-class events are displayed as orange
        $color = "orange";
    else if($activity->type == "Swim" || $activity->type == "Canoeing" || $activity->type == "Kayaking" || $activity->type == "Kitesurf" || $activity->type == "Rowing" || $activity->type == "StandUpPaddling" || $activity->type == "Surfing"  || $activity->type == "Windsurf")
        // Swim-class events are displayed as blue
        $color = "blue";
    else if($activity->type == "AlpineSki" || $activity->type == "BackcountrySki" || $activity->type == "RollerSki" || $activity->type == "Snowboard" || $activity->type == "Snowshoe" || $activity->type == "IceSkate" || $activity->type == "NordicSki")
        // Snowsports are displayed as teal
        $color = "teal";
    else if($activity->type == "Crossfit" || $activity->type == "WeightTraining" || $activity->type == "Workout" || $activity->type == "Yoga" || $activity->type == "InlineSkate" || $activity->type == "RockClimbing")
        // Other Strava events are displayed as green
        $color = "green";
    else
        // Any other activities (null, unlabeled, etc) are set to a default color
        $color = "";
    $calendar->add_event($activity->name, $activity->start_date, 1, $activity->activity_id, $color);
}
?>
<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="{{ asset('css/calendar.css') }}" type="text/css">
    <link rel="stylesheet" href="{{ asset('css/interface.css') }}" type="text/css">

    <style>
        #test {
            font-size: 100px;
            color: white;
            display: none;
        }
    </style>
</head>
<body>
    <a id="backLink" href="{{URL::to('/')}}"><< Back to Landing Page</a>
    <h1>Calendar</h1>
    <p>Athlete ID: {{$athlete_id}}</p>
    <?=$calendar?>

    <div id="focus_panel" hidden = true>
        <table>
            <tbody>
                <tr>
                    <td class="focus" style="font-size: large">Name: </td>
                    <td class="info" id="foc_title"></td>
                    <td class="focus">Type: </td>
                    <td class="info" id="foc_type"></td>
                </tr>
                <tr>
                    <td class="focus">Date: </td>
                    <td class="info" id="foc_date"></td>
                    <td class="focus">Time: </td>
                    <td class="info" id="foc_time"></td>
                </tr>
                <tr>
                    <td class="focus">Distance: </td>
                    <td class="info" id="foc_dist"></td>
                    <td class="focus">Elevation: </td>
                    <td class="info" id="foc_elev"></td>
                </tr>
                <tr>
                    <td class="focus">Kudos: </td>
                    <td class="info" id="foc_kudos"></td>
                </tr>
            </tbody>
        </table>
    </div>

    <!--<div id="focus_panel" hidden = true>
        <label class="focus" style="font-size: large">Name: </label>
        <span class="info" id="foc_title"></span>
        <br>
        <label class="focus">Type: </label>
        <span class="info" id="foc_type"></span>
        <br>
        <label class="focus">Date: </label>
        <span class="info" id="foc_date"></span>
        <br>
        <label class="focus">Time: </label>
        <span class="info" id="foc_time"></span>
        <br>
        <label class="focus">Distance: </label>
        <span class="info" id="foc_dist"></span>
        <br>
        <label class="focus">Elevation: </label>
        <span class="info" id="foc_elev"></span>
        <br>
        <label class="focus">Kudos: </label>
        <span class="info" id="foc_kudos"></span>
    </div>-->
</body>
</html>
<script>
    function focusEvent(myEvent) {
        let panel = document.getElementById("focus_panel");
        panel.hidden = false;
        let json = JSON.parse('<?=json_encode($monthsActivities)?>');
        let activity_id = myEvent.getAttribute('data-value');
        let found = false;
        let i = 0;
        while(!found) {
            if (JSON.stringify(json[i]["activity_id"]) === activity_id) {
                found = true;
            } else {
                i++;
            }
        }

        document.getElementById("foc_title").innerHTML = json[i]["name"]
        document.getElementById("foc_type").innerHTML = json[i]["type"]
        let date = convertDate(JSON.stringify(json[i]["start_date"]).substring(1, 11));
        document.getElementById("foc_date").innerHTML = date
        document.getElementById("foc_time").innerHTML = json[i]["elapsed_time"] + " seconds"
        document.getElementById("foc_dist").innerHTML = json[i]["distance"]
        document.getElementById("foc_elev").innerHTML = json[i]["total_elevation_gain"]
        document.getElementById("foc_kudos").innerHTML = json[i]["kudos_count"]
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

    function blurEvent() {
        document.getElementById("focus_panel").hidden = true;
    }

    function changeMonth(goForward) {
        let today = new Date();

        let str = "{{$date}}";
        if(str != "") {
            // If $date is set, we should use that instead of today
            let y = Number(str.substring(0, 4));
            let m = Number(str.substring(5, 7));
            let d = Number(str.substring(8));
            today = new Date(y, m-1, d);
        }

        let day = String(today.getDate()).padStart(2, '0');
        let year = today.getFullYear();
        let month;
        if(goForward) {
            // Next month
            if(today.getMonth() < 11) {
                // Add 1 for proper adjustment and 1 for the month increase
                month = String(today.getMonth() + 2).padStart(2, '0');
            } else {
                // December, has to loop back to January
                month = String(1).padStart(2, '0');
                year++;
            }
        } else {
            // Previous Month
            if(today.getMonth() == 0){
                // January, has to go back to last December
                month = String(12).padStart(2, '0');
                year--;
            } else {
                // JS months start at 0, so we don't have to adjust
                month = String(today.getMonth()).padStart(2, '0');
            }
        }

        // Handle if the current date is higher than possible in the next month
        let isThirtyMonth = month == "09" || month == "04" || month == "06" || month == "11";
        if(month == "02" && today.getDate() >= 28) {
            if(year % 4 == 0 && today.getDate() >= 29) {
                // Leap February, cap at 29
                day = "29";
            } else {
                // Regular February, cap at 28
                day = "28";
            }
        } else if (isThirtyMonth && today.getDate() >= 30) {
            // If over 30 days, cap at 30 for these months
            day = "30";
        }

        // Recompile date into Y-m-d
        let date = "" + year + '-' + month + '-' + day;

        // Go to page, place variables in URL
        window.location.href = "/calendar?athlete_id={{$athlete_id}}&date="+date;
    }

</script>