<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ActivityController extends Controller
{
    public function getActivityData($userID) {
        return DB::table("athlete")
            ->select("*")
            ->where("userID", "=", $userID)
            ->get();
    }
}