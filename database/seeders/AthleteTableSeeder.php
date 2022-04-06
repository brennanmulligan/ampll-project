<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AthleteTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('athlete')->insert([
            'athlete_id' => '123456789',
            'username' => 'johntest',
            'first_name' => 'John',
            'last_name' => 'Test'
        ]);

        DB::table('athlete')->insert([
            'athlete_id' => '987654321',
            'username' => 'gregjones',
            'first_name' => 'Greg',
            'last_name' => 'Jones'
        ]);
    }
}
