<?php

namespace Tests\Feature;

use App\Http\Controllers\ActivityController;
use App\Http\Controllers\AthleteController;
use App\Objects\Activity;
use App\Objects\Athlete;
use Database\Seeders\ActivityTableSeeder;
use Database\Seeders\AthleteTableSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ActivityControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Tests that we properly store multiple activites into the DB
     *
     * @return void
     */
    public function testStoreActivities()
    {
        $this->seed(AthleteTableSeeder::class);
        $activityController = new ActivityController();
        $activity1 = new Activity('5017254429', 'Afternoon Run', 'Run');
        $activity2 = new Activity('5017254430', 'Afternoon Run', 'Run');
        $activities = [$activity1, $activity2];
        $this->assertDatabaseMissing('activity', ['activity_id' => '5017254429']);
        $this->assertDatabaseMissing('activity', ['activity_id' => '5017254430']);
        $activityController->storeActivities('123456789', $activities);
        $this->assertDatabaseHas('activity', ['activity_id' => '5017254429']);
        $this->assertDatabaseHas('activity', ['activity_id' => '5017254430']);
    }

    public function testGetAllActivitiesForAthlete() {
        $this->seed(AthleteTableSeeder::class);
        $this->seed(ActivityTableSeeder::class);
        $activityController = new ActivityController();
        $activities = $activityController->getAllActivityData('123456789');
        $this->assertNotEmpty($activities);
        $id1 = $activities[0]->activity_id;
        $id2 = $activities[1]->activity_id;
        $this->assertEquals('111111111', $id1);
        $this->assertEquals('999999999', $id2);
    }
}
