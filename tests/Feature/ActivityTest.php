<?php

namespace Tests\Feature;

use App\Http\Controllers\ActivityController;
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
        $activityController = new ActivityController();

        $athlete = $activityController->getAllActivityData('69703678988889');

        self::assertNotEmpty($athlete);

        self::assertEquals(69703678988889, $athlete[0]->athlete_id);
    }
}