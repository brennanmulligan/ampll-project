<?php

use App\Http\Controllers\AthleteController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserLogin;
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

Route::get('user_data', [AthleteController::class,'getData']);