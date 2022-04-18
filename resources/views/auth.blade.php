<?php
header("Location: https://www.strava.com/oauth/authorize?client_id=" . config("AppConstants.client_id") .
    "&redirect_uri=http://localhost:8000/auth_response&response_type=code&scope=activity:read_all");
exit();
?>