<?php

namespace App\Http\Middleware;

use App\Objects\Activity;
use App\Objects\Athlete;
use App\Objects\AuthorizationData;
use App\Objects\RefreshData;
use App\Objects\Updates;
use App\Objects\WebhookData;

class JsonParser
{
    /**
     * A function to get the information from specific fields in a decoded JSON object
     * @param $authorizationData A decoded JSON object that we will parse in order to get the data we need to store
     * @return AuthorizationData
     */
    function parseAuthorizationData($authorizationData)
    {
        $athlete = $this->parseAthleteData($authorizationData->athlete);
        return new AuthorizationData($authorizationData->access_token, $authorizationData->refresh_token, $athlete);
    }

    /**
     * A function to get the information from specific fields in a decoded JSON object
     * @param $refreshData A decoded JSON object that we will parse in order to get the data we need to store
     * @return RefreshData
     */
    function parseRefreshData($refreshData)
    {
        return new RefreshData($refreshData->access_token, $refreshData->refresh_token);
    }

    /**
     * A function to get the information from specific fields in a decoded JSON object
     * @param $athleteData mixed A decoded JSON object that we will parse in order to get the data we need to store
     * @return Athlete
     */
    function parseAthleteData(mixed $athleteData)
    {
        return new Athlete($athleteData->id, $athleteData->username, $athleteData->firstname, $athleteData->lastname,
            $athleteData->city, $athleteData->state, $athleteData->country, $athleteData->sex, $athleteData->updated_at);
    }

    /**
     * A function that will get all of the activities from an athlete by parsing a JSON object
     * @param $activitiesData mixed A decoded JSON object that holds the data for all of an athlete's activities
     * in the last 6 months
     * @return array
     */
    function parseActivitiesData(mixed $activitiesData): array
    {
        $activities = array();
        foreach ($activitiesData as $activity) {
            $activities[] = new Activity($activity->id, $activity->name,
                $activity->type, $activity->elapsed_time,
                $activity->distance, $activity->total_elevation_gain,
                $activity->start_date, $activity->start_date_local,
                $activity->utc_offset, $activity->kudos_count, $activity->private);
        }
        return $activities;
    }

    /**
     * @param mixed $data
     * @return WebhookData
     */
    function parseWebhookData(mixed $data): WebhookData
    {
        $updates = new Updates();
        if (isset($data['updates'])) {
            // set our updates values
            if (isset($data['updates']['title'])) {
                $updates->setTitle($data['updates']['title']);
            }
            if (isset($data['updates']['type'])) {
                $updates->setType($data['updates']['type']);
            }
            if (isset($data['updates']['private'])) {
                $updates->setPrivate($data['updates']['private']);
            }
            if (isset($data['updates']['authorized'])) {
                $updates->setAuthorized($data['updates']['authorized']);
            }
        }

        return new WebhookData($data['object_type'], $data['object_id'], $data['aspect_type'], $data['owner_id'],
            $data['subscription_id'], $data['event_time'], $updates);
    }
}
