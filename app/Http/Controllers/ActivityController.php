<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ActivityController extends Controller
{
    public function getActivityData($userID) {
        $activityData = DB::Select("SELECT * FROM user_activity_data WHERE userID = $userID");

        return $activityData;
    }
}