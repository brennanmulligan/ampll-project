<?php

namespace App\Http\Controllers;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;



class UserLogin extends Controller
{
    function response(): Response
    {
        $code = $_GET['code'];
        return Http::post('https://www.strava.com/api/v3/oauth/token', [
            'client_id' => '77760',
            'client_secret' => 'e37c977b72b37bc23b804acfd83f7f1ac3c78943',
            'code' => $code,
            'grant_type' => 'authorization_code'
        ]);
    }
}