<?php

namespace App\Http\Controllers;

use App\Models\Auth;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function getAuthTokens($athlete_id) {
        return Auth::where("athlete_id", "=", $athlete_id)
            ->get();
    }
}
