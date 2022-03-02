<?php

use App\Http\Controllers\StravaAPIController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('auth_response',[UserLogin::class,'response']);

Route::view('login','auth');

// Routes to pull data from strava using their API
Route::get('user_data', [StravaAPIController::class,'getUserData']);
Route::get('activities_data', [StravaAPIController::class,'getActivitiesData']);
