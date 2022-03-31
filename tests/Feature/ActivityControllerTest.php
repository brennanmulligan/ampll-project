<?php

namespace Tests\Feature;

use App\Http\Controllers\ActivityController;
use App\Http\Controllers\AthleteController;
use App\Objects\Activity;
use App\Objects\Athlete;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ActivityControllerTest extends TestCase
{
    use RefreshDatabase;
    private Activity $activity1;
    private Activity $activity2;
    private Athlete $athlete;
    private ActivityController $activityController;

    function __construct()
    {
        parent::__construct();
        parent::setUp();
    }

    protected function setUp(): void {
        $this->athlete = new Athlete('37134971', 'scotbooker', 'Scott', 'Bucher',
            'Somewhere', 'Pennsylvania', 'United States', 'M');
        $this->activity1 = new Activity('5017254429', 'Afternoon Run', 'Run', '1750',
            '5230.00', '33', '2022-12-14T21:39:47Z', '2022-12-14T17:39:47Z',
            '-14400', '0');
        $this->activity2 = new Activity('5017254430', 'Afternoon Run', 'Run', '1800',
            '5200.00', '30', '2022-12-15T21:39:47Z', '2022-12-15T17:39:47Z',
            '-14400', '0');
        $this->activityController = new ActivityController();
        $athleteController = new AthleteController();
        $athleteController->addOrUpdate($this->athlete);
    }

    /**
     * Tests that we properly store multiple activites into the DB
     *
     * @return void
     */
    public function testStoreActivities()
    {
        $activities = [$this->activity1, $this->activity2];
        $this->assertDatabaseMissing('activity', ['activity_id' => '5017254429']);
        $this->assertDatabaseMissing('activity', ['activity_id' => '5017254430']);
        $this->activityController->storeActivities($this->athlete->getId(), $activities);
        $this->assertDatabaseHas('activity', ['activity_id' => '5017254429']);
        $this->assertDatabaseHas('activity', ['activity_id' => '5017254430']);
    }

    public function testGetAllActivitiesForAthlete() {
        $activities = [$this->activity1, $this->activity2];
        $this->assertDatabaseMissing('activity', ['athlete_id' => $this->athlete->getId()]);

        $this->activityController->storeActivities($this->athlete->getId(), $activities);

        $this->assertDatabaseHas('activity', ['activity' => $activities[0]->getId()]);
        $this->assertDatabaseHas('activity', ['athlete_id' => $activities[1]->getId()]);
    }
}
