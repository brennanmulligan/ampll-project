<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class NewAthlete extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table("athlete")->insert([
            'athlete_id' => '69703678988889',
            'username' => 'mzuck2851',
            'first_name' => 'Mark',
            'last_name' => 'Zuckerbong',
            'city' => 'Nowhere',
            'state' => 'OH',
            'country' => 'USA',
            'sex' => 'Male'
        ]);
    }
}
