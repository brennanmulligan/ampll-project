<?php

use App\Http\Controllers\GatewayController;
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

// Gets
Route::get('/', function () {
    return view('interface');
});

Route::get('auth_response',[GatewayController::class,'login']);

Route::get('test/{athleteID}', [\App\Http\Controllers\StravaAPIController::class, 'getActivitiesData']);

// Views
Route::view('login','auth')->name('login');

Route::view('ui', 'interface');

// Posts
Route::post('webhook', 'App\Http\Controllers\WebhookController@handle');

