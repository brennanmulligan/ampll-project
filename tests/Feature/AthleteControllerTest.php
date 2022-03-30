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

    /**
     * Test that we can insert an athlete into our database
     *
     * @return void
     */
    public function testInsertAthlete()
    {
        $athleteController = new AthleteController();
        $athlete = new Athlete('37134970', 'alexschreffler', 'Alex', 'Schreffler',
            'Herndon', 'Pennsylvania', 'United States', 'M');

        $this->assertDatabaseMissing('athlete', ['athlete_id' => '37134970']);

        $athleteController->addOrUpdate($athlete);

        $this->assertDatabaseHas('athlete', ['athlete_id' => '37134970']);
    }

    /**
     * Test that we can update and existing athlete
     * @return void
     */
    public function testUpdateAthlete() {
        $athleteController = new AthleteController();
        $athlete = new Athlete('37134970', 'alexschreffler', 'Alex', 'Schreffler',
            'Herndon', 'Pennsylvania', 'United States', 'M');

        $this->assertDatabaseMissing('athlete', ['athlete_id' => '37134970']);

        $athleteController->addOrUpdate($athlete);
        $this->assertDatabaseHas('athlete', ['username' => 'alexschreffler']);

        $newAthlete = new Athlete('37134970', 'schreffleralex', 'Alex', 'Schreffler',
            'Herndon', 'Pennsylvania', 'United States', 'M');

        $athleteController->addOrUpdate($newAthlete);
        $this->assertDatabaseHas('athlete', ['username' => 'schreffleralex']);
    }

    /**
     * Test that we can properly get an athlete from the DB
     * @return void
     */
    public function testGetAthlete() {
        $athleteController = new AthleteController();
        $athlete = new Athlete('37134970', 'alexschreffler', 'Alex', 'Schreffler',
            'Herndon', 'Pennsylvania', 'United States', 'M');
        $athleteController->addOrUpdate($athlete);
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
        $athlete = new Athlete('37134970', 'alexschreffler', 'Alex', 'Schreffler',
            'Herndon', 'Pennsylvania', 'United States', 'M');
        $athlete2 = new Athlete('37134971', 'scotbooker', 'Scott', 'Bucher',
            'Somewhere', 'Pennsylvania', 'United States', 'M');
        $athleteController->addOrUpdate($athlete);
        $athleteController->addOrUpdate($athlete2);
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
