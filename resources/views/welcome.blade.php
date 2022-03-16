<?php
$athlete_controller = new \App\Http\Controllers\AthleteController();
?>
<!DOCTYPE html>
<html>
<style>
    * {
        box-sizing: border-box;
    }

    /* Create two equal columns that floats next to each other */
    .column {
        float: left;
        width: 50%;
        padding: 10px;
    }

    /* Clear floats after the columns */
    .row:after {
        content: "";
        display: table;
        clear: both;
    }
</style>
</head>
<body>

<h1>Landing Page</h1>

<div class="row">
    <div class="column">
        <h2>Admin UI</h2>
        <a href = "ui">
            <button>GO!</button>
        </a>
    </div>
    <div class="column">
        <h2>Calendar UI</h2>
        <form action="/calendar" id="athlete_select">
            <select name="athlete_id" id="athlete_id" form="athlete_select">
                <option selected disabled>Switch User</option>
                @foreach ($athlete_controller->getAllAthletes() as $athlete)
                    <option value="{{ $athlete->athlete_id }}">{{ $athlete->username }}</option>
                @endforeach
            </select>
            <input type="submit">
        </form>
    </div>
</div>

</body>
</html>
