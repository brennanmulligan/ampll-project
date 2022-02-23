<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ActivityTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_example()
    {
        /*
         * <?php
         *      $a = array("Volvo" => "XC90", "BMW" => "X5", "Toyota" => "Highlander");
         *      print_r(array_keys($a));
         *  ?>
         */
        $num = 40;

        self::assertEquals($num, 41, "Test FAILED");
    }
}