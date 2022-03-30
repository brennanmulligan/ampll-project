<?php

namespace Tests\Feature;

use App\Http\Controllers\AthleteController;
use App\Objects\Athlete;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AthleteControllerTest extends TestCase
{
    use RefreshDatabase;

    private Athlete $athlete1;
    private Athlete $athlete2;

    function __construct()
    {
        parent::__construct();
        parent::setUp();
    }

    protected function setUp(): void {
        $this->athlete1 = new Athlete('37134970', 'alexschreffler', 'Alex', 'Schreffler',
            'Herndon', 'Pennsylvania', 'United States', 'M');
        $this->athlete2 = new Athlete('37134971', 'scotbooker', 'Scott', 'Bucher',
            'Somewhere', 'Pennsylvania', 'United States', 'M');
    }

    /**
     * Test that we can insert an athlete into our database
     *
     * @return void
     */
    public function testInsertAthlete()
    {
        $athleteController = new AthleteController();

        $this->assertDatabaseMissing('athlete', ['athlete_id' => '37134970']);

        $athleteController->addOrUpdate($this->athlete1);

        $this->assertDatabaseHas('athlete', ['athlete_id' => '37134970']);
    }

    /**
     * Test that we can update and existing athlete
     * @return void
     */
    public function testUpdateAthlete() {
        $athleteController = new AthleteController();

        $this->assertDatabaseMissing('athlete', ['athlete_id' => '37134970']);

        $athleteController->addOrUpdate($this->athlete1);
        $this->assertDatabaseHas('athlete', ['username' => 'alexschreffler']);

        //mimic someone changing their username
        $updatedAthlete = new Athlete('37134970', 'schreffleralex', 'Alex', 'Schreffler',
            'Herndon', 'Pennsylvania', 'United States', 'M');

        $athleteController->addOrUpdate($updatedAthlete);
        $this->assertDatabaseHas('athlete', ['username' => 'schreffleralex']);
    }

    /**
     * Test that we can properly get an athlete from the DB
     * @return void
     */
    public function testGetAthlete() {
        $athleteController = new AthleteController();
        //dummy data in test db so we don't need to add
        $athleteController->addOrUpdate($this->athlete1);
        $athleteFromDB = $athleteController->getAthlete('37134970');
        self::assertNotEmpty($athleteFromDB);
        $id = $athleteFromDB->athlete_id;
        self::assertEquals('37134970', $id);
    }

    /**
     * Test that we can get multiple athletes from the DB
     * @return void
     */
    public function testGetAllAthletes() {
        $athleteController = new AthleteController();
        //make it so we can have dummy data in the db so we dont need to add them for the test
        $athleteController->addOrUpdate($this->athlete1);
        $athleteController->addOrUpdate($this->athlete2);
        $athletes = $athleteController->getAllAthletes();
        self::assertNotEmpty($athletes);
        $athlete1FromDB = $athletes[0];
        $athlete2FromDB = $athletes[1];
        $id1 = $athlete1FromDB->athlete_id;
        $id2 = $athlete2FromDB->athlete_id;
        self::assertEquals('37134970', $id1);
        self::assertEquals('37134971', $id2);
    }

    /**
     * Test that we can get athletes after a specified refreshed_at time
     * @return void
     */
    public function testGetAthleteAfterTime() {
        
    }
}
