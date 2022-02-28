<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ActivityController extends Controller
{
    public function getAllActivityData($athlete_id) {
        return Activity::where("athlete_id", "=", $athlete_id)
            ->get();
    }
}