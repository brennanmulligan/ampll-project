<?php
$activity_controller = new \App\Http\Controllers\ActivityController();
$athlete_controller = new \App\Http\Controllers\AthleteController();

if (isset($_GET["refresh"]) && $_GET["id_sel"] != 0) {
    $gatewayController = new \App\Http\Controllers\GatewayController();

    if ($gatewayController->refreshData($_GET["id_sel"]) == -1) {
        header('Location: /login');
        exit();
    }
} else {
    redirect('ui');
}

try {
    $athlete_id = $_GET["id_sel"];
} catch (ErrorException) {
    $athlete_id = 0; //ID by default
}
?>
<!DOCTYPE html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Demo Output</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/interface.css') }}" type="text/css">
</head>
<body>
    <a id="backLink" href="{{URL::to('/')}}"><< Back to Landing Page</a>

    <form action="login">
        <input type="submit" name="register" value="Register"/>
    </form>

    <h2>Change User to Display</h2>
    <form action="/ui" id="athlete_select">
        <select name="id_sel" id="id_sel" form="athlete_select">
            <option selected="disabled">Switch User</option>
            @foreach ($athlete_controller->getAllAthletes() as $athlete)
                <option value="{{ $athlete->athlete_id }}">{{ $athlete->username }}</option>
            @endforeach
        </select>
        <input type="submit">
    </form>

    <br>

    <a href='ui?id_sel=<?php echo $athlete_id ?>&refresh=Refresh'>
        <button>Refresh</button>
    </a>

    <br>
    <h2>Activities Stored for User</h2>
    <p>Displaying output for athlete id: {{ $athlete_id }}</label></p>

    <?php
    $all_activities = $activity_controller->getAllActivityData($athlete_id);
    ?>

    <table id="output_table">
        <tr>
            <th class="cell">Activity ID</th>
            <th class="cell">Name</th>
            <th class="cell">Type</th>
            <th class="cell">Elapsed Time</th>
            <th class="cell">Distance</th>
            <th class="cell">Elevation</th>
            <th class="cell">Start Date</th>
            <th class="cell">Kudos Count</th>
        </tr>
        @foreach ($all_activities as $activity)
            <tr>
                <td class="cell">{{ $activity->activity_id }}</td>
                <td class="cell">{{ $activity->name }}</td>
                <td class="cell">{{ $activity->type }}</td>
                <td class="cell">{{ $activity->elapsed_time }}</td>
                <td class="cell">{{ $activity->distance }}</td>
                <td class="cell">{{ $activity->total_elevation_gain }}</td>
                <td class="cell">{{ $activity->start_date_local }}</td>
                <td class="cell">{{ $activity->kudos_count }}</td>
            </tr>
        @endforeach
    </table>
</body>
</html>