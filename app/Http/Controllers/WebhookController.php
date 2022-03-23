<?php

namespace App\Http\Controllers;

use App\Http\Middleware\JsonParser;
use Illuminate\Http\Client\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Objects\WebhookData;

class WebhookController extends Controller
{
    public function handle(Request $request)
    {
       $jsonParser = new JsonParser();

       $json = $request->json()->all();

       $data = $jsonParser->parseWebhookData($json);

        if ($data->getAspectType() == 'create' || $data->getAspectType() == 'update') {
            $gatewayController = new GatewayController();
            if ($data->getObjectType() == 'activity') {
                $gatewayController->storeActivitiesData($data->getOwnerId());
            }
        }
    }


    /**
     * @param Request $request - receives strava validation request when subscribing to events
     * @return JsonResponse - respond to strava's verification challenge
     */
    public function validate_subscription(Request $request): JsonResponse
    {
        return response()->json(["hub.challenge" => $request->input("hub_challenge")]);
    }
}
