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

    public function getMonthActivityData($athlete_id, $requestedDate) {
        $year = substr($requestedDate, 0, 4);
        $month = substr($requestedDate, 5, 2);
        $isThirtyMonth = $month == 4 || $month == 6 || $month == 9 || $month == 11;

        $startDate = $year . "-" . $month . "-01T00:00:00";
        if($isThirtyMonth) {
            $endDate = $year . "-" . $month . "-30T11:59:59";
        } else if($month == 2) {
            if((intval($year) % 4) == 0) {
                $endDate = $year . "-" . $month . "-29T11:59:59";
            }
            else {
                $endDate = $year . "-" . $month . "-28T11:59:59";
            }
        } else {
            $endDate = $year . "-" . $month . "-31T11:59:59";
        }

        return Activity::where(function ($query) use ($endDate, $startDate, $athlete_id) {
        $query->where("athlete_id", "=", $athlete_id)
            ->whereBetween("start_date", [$startDate, $endDate]);
            })->get();
    }

    /**
     * stores an array of activities into the DB
     * @param $activities \App\Objects\Activity[]
     * @return void
     */
    public function storeActivities($athleteID, $activities) {
        foreach($activities as $activity) {
            Activity::updateOrCreate(
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