<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ActivityTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('activity')->insert([
            'activity_id' => '111111111',
            'athlete_id' => '123456789',
            'name' => 'Night Swim',
            'type' => 'Swim'
        ]);

        DB::table('activity')->insert([
            'activity_id' => '999999999',
            'athlete_id' => '123456789',
            'name' => 'Morning Swim',
            'type' => 'Swim'
        ]);
    }
}
