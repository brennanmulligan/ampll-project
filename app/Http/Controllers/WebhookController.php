<?php

namespace App\Http\Controllers;

use Illuminate\Http\Client\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class WebhookController extends Controller
{
    public function handle(Request $request)
    {
       //
    }


    /**
     * @param Request $request - receives strava validation request when subscribing to events
     * @return \Illuminate\Http\JsonResponse - respond to strava's verification challenge
     */
    public function validate_subscription(Request $request)
    {
        return response()->json(["hub.challenge" => $request->input("hub_challenge")]);
    }
}
