<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class AuthData extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table("auth")->insert([
            'athlete_id' => '69703678988888',
            'refresh_token' => Str::random(40),
            'access_token' => Str::random(40),
        ]);
    }
}
