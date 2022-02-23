<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class AthleteController extends Controller
{
    function getData()
    {
        $authToken = 'Bearer d5ca84d5e6912ec1eca000e93122ddd1225d9872';
        $userData = Http::withHeaders(['Authorization' => $authToken])->
            get('https://www.strava.com/api/v3/athlete');
        $decodedUserData = json_decode($userData, false);
        $userID = $decodedUserData->id;
        echo $userID . '<br>';
        $username = $decodedUserData->username;
        echo $username . '<br>';
        $firstName = $decodedUserData->firstname;
        echo $firstName . '<br>';
        $lastName = $decodedUserData->lastname;
        echo $lastName . '<br>';
        $city = $decodedUserData->city;
        echo $city . '<br>';
        $state = $decodedUserData->state;
        echo $state . '<br>';
        $country = $decodedUserData->country;
        echo $country . '<br>';
        $sex = $decodedUserData->sex;
        echo $sex . '<br>';
    }
}
