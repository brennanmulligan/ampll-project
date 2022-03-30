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
    private String $id;

    function __construct()
    {
        parent::__construct();
        parent::setUp();
    }

    protected function setUp(): void {
        $this->id = '37134970';
        $this->activity1 = new Activity('5017254429', 'Afternoon Run', 'Run', '1750',
            '5230.00', '33', '2022-12-14T21:39:47Z', '2022-12-14T17:39:47Z',
            '-14400', '0');
        $this->activity2 = new Activity('5017254430', 'Afternoon Run', 'Run', '1800',
            '5200.00', '30', '2022-12-15T21:39:47Z', '2022-12-15T17:39:47Z',
            '-14400', '0');
        $athlete = new Athlete('37134970', 'alexschreffler', 'Alex', 'Schreffler',
            'Herndon', 'Pennsylvania', 'United States', 'M');
        $athleteController = new AthleteController();
        $athleteController->addOrUpdate($athlete);
    }

    /**
     * Tests that we properly store multiple activites into the DB
     *
     * @return void
     */
    public function testStoreActivities()
    {
        $activities = [$this->activity1, $this->activity2];
        $activityController = new ActivityController();
        $this->assertDatabaseMissing('activity', ['activity_id' => '5017254429']);
        $this->assertDatabaseMissing('activity', ['activity_id' => '5017254430']);
        $activityController->storeActivities($this->id, $activities);
        $this->assertDatabaseHas('activity', ['activity_id' => '5017254429']);
        $this->assertDatabaseHas('activity', ['activity_id' => '5017254430']);
    }
}
