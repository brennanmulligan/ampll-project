<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ActivityController extends Controller
{
    public function getAllActivityData($athlete_id) {
        return Activity::where("athlete_id", "=", $athlete_id)
            ->get();
    }

    /**
     * stores an array of activities into the DB
     * @param $activities \App\Objects\Activity[]
     * @return void
     */
    public function storeActivities($athleteID, $activities) {
        foreach($activities as $activity) {
            Activity::updateOrInsert(
                ['activity_id' => $activity->getId()],
                ['athlete_id' => $athleteID, 'name' => $activity->getName(), 'type' => $activity->getType(),
                    'elapsed_time' => $activity->getElapsedTime(), 'distance' => $activity->getDistance(),
                    'total_elevation_gain' => $activity->getTotalElevationGain(),
                    'start_date' => (String)$activity->getStartDate(), 'start_date_local' => (String)$activity->getStartDateLocal(),
                    'utc_offset' => $activity->getUTCOffset(), 'kudos_count' => $activity->getKudosCount()]
            );
        }
        //when we make changes to an athlete's activities, we also want to update the athlete's refreshed_at var
        DB::table('athlete')
            ->updateOrInsert(
                ['athlete_id' => $athleteID],
                ['refreshed_at' => time()]
            );
    }
}