<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ActivityData extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table("activity")->insert([
            'activity_id' => '12343545645788',
            'athlete_id' => '69703678988889',
            'name' => 'John Doe',
            'type' => 'Walking',
            'elapsed_time' => 18373,
            'distance' => 2026.43,
            'total_elevation_game' => 346,
            'start_date' => '2022-02-15T18:02:13Z',
            'start_date_local' => '2022-02-15T18:02:13Z',
            'utc_offset' => -28800,
            'kudos_count' => 0
        ]);
    }
}
