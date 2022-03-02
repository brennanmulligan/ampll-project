<?php
$activity_controller = new \App\Http\Controllers\ActivityController();
$athlete_controller = new \App\Http\Controllers\AthleteController();
try{
    $athlete_id = $_GET["id_sel"];
} catch(ErrorException) {
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

    <style>
        body {
            font-family: 'Nunito', sans-serif;
        }
    </style>
<body>

<form action="login">
    <input type="submit" name="register" value="Register"/>
</form>

<h2>Change User to Display</h2>
<form action="/ui" id="athlete_select">
<select name="id_sel" id="id_sel" form="athlete_select">
    <option selected disabled>Switch User</option>
    @foreach ($athlete_controller->getAllAthletes() as $athlete)
        <option value="{{ $athlete->athlete_id }}">{{ $athlete->username }}</option>
    @endforeach
</select>
    <input type="submit">
</form>

<br>
<h2>Activities Stored for User</h2>
<p>Displaying output for athlete id: {{ $athlete_id }}</label></p>

<?php
    $all_activities = $activity_controller->getAllActivityData($athlete_id);
?>
<style>
    table{
        border-collapse: collapse;
    }
    td, th {
        border: 1px solid black;
        padding: 5px;
    }
</style>

<table id="output_table">
<tr>
<th>Activity ID</th>
<th>Name</th>
<th>Type</th>
<th>Elapsed Time</th>
<th>Distance</th>
<th>Elevation</th>
<th>Start Date</th>
<th>Kudos Count</th>
</tr>
@foreach ($all_activities as $activity)
<tr>
<td>{{ $activity->activity_id }}</td>
<td>{{ $activity->name }}</td>
<td>{{ $activity->type }}</td>
<td>{{ $activity->elapsed_time }}</td>
<td>{{ $activity->distance }}</td>
<td>{{ $activity->total_elevation_gain }}</td>
<td>{{ $activity->start_date_local }}</td>
<td>{{ $activity->kudos_count }}</td>
</tr>
@endforeach
</table>
</body>
</html>