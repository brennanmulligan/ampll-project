<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AuthTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //encryption iv has no default value but isn't being tested here. just needs to be a 16 byte string (16 ch long)
        DB::table('auth')->insert([
            'athlete_id' => '123456789',
            'refresh_token' => '1a2b3c4d5e',
            'access_token' => 'a1b2c3d4e5',
            'valid' => 1,
            'encryption_iv' => 'abcdefghijklmnop'
        ]);

        DB::table('auth')->insert([
            'athlete_id' => '987654321',
            'refresh_token' => '5e4d3c2b1a',
            'access_token' => 'e5d4c3b2a1',
            'valid' => 0,
            'encryption_iv' => 'abcdefghijklmnop'
        ]);
    }
}
