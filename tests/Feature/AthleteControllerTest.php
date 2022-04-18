<?php

namespace Tests\Feature;

use App\Http\Controllers\AthleteController;
use App\Objects\Athlete;
use Database\Seeders\AthleteTableSeeder;
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
        $athlete = new Athlete('37134970', 'alexschreffler', 'Alex', 'Schreffler',
            'Herndon', 'Pennsylvania', 'United States', 'M');

        $athleteController = new AthleteController();

        $this->assertDatabaseMissing('athlete', ['athlete_id' => '37134970']);

        $athleteController->addOrUpdate($athlete);

        $this->assertDatabaseHas('athlete', ['athlete_id' => '37134970']);
    }

    /**
     * Test that we can update and existing athlete
     * @return void
     */
    public function testUpdateAthlete() {
        $this->seed(AthleteTableSeeder::class);

        $athleteController = new AthleteController();

        $updatedAthlete = new Athlete('123456789', 'johnnytest');

        $athleteController->addOrUpdate($updatedAthlete);
        $this->assertDatabaseHas('athlete', ['username' => 'johnnytest']);
    }

    /**
     * Test that we can properly get an athlete from the DB
     * @return void
     */
    public function testGetAthlete() {
        $this->seed(AthleteTableSeeder::class);
        $athleteController = new AthleteController();
        $athleteFromDB = $athleteController->getAthlete('123456789');
        self::assertNotEmpty($athleteFromDB);
        $id = $athleteFromDB->athlete_id;
        self::assertEquals('123456789', $id);
    }

    /**
     * Test that we can get multiple athletes from the DB
     * @return void
     */
    public function testGetAllAthletes() {
        $this->seed(AthleteTableSeeder::class);
        $athleteController = new AthleteController();
        $athletes = $athleteController->getAllAthletes();
        self::assertNotEmpty($athletes);
        $id1 = $athletes[0]->athlete_id;
        $id2 = $athletes[1]->athlete_id;
        self::assertEquals('123456789', $id1);
        self::assertEquals('987654321', $id2);
    }
}
