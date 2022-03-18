<?php

namespace App\Factories;

use App\Http\Controllers\ActivityController;
use App\Http\Controllers\AthleteController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\StravaAPIController;
use tests\MockObjects\MockActivityController;
use tests\MockObjects\MockAthleteController;
use tests\MockObjects\MockAuthController;
use tests\MockObjects\MockStravaAPIController;

/**
 * Factory to create controller objects based on whether we are testing (mock objects) or not testing (real obejcts)
 */
class ControllerFactory
{
    private static $instance = null;
    private bool $test = False;

    private function __construct($test)
    {
        if ($test == True) {
            $this->test = True;
        }
    }

    /**
     * Don't want to have to pass in a variable to tell us we're in testing mode when we are just using production stuff
     */
    private function getControllerFactoryProd(): ?ControllerFactory
    {
        if (self::$instance == null)
        {
            self::$instance = new ControllerFactory(False);
        }

        return self::$instance;
    }

    /**
     * We should only ever want to pass in the fact that we will be in testing mode in the tests themselves
     * @param $test bool is true when we want to enter testing mode, false if not but should never enter false.
     */
    private function getControllerFactoryTest($test): ?ControllerFactory
    {
        if (self::$instance == null)
        {
            self::$instance = new ControllerFactory($test);
        }

        return self::$instance;
    }

    /**
     * function that is called when the functin name isn't found.
     * we're using it to overload our singleton constructor
     * @param $name String the name of the attempted function call
     * @param $arguments array the arguements passed in
     * @return void
     */
    public function __call(string $name, array $arguments)
    {
        $numberOfArguments = count($arguments);
        if ($name == "getControllerFactory") {
            //production path where we don't need/want to pass in any variable
            if ($numberOfArguments == 0) {
                $this->getControllerFactoryProd();
            } else { //test path where we need to specify that we want to be in test mode
                //we know that we should only get 1 variable
                $this->getControllerFactoryTest($arguments[0]);
            }
        }
    }

    // creation functions for the controllers. returns a mock object if test is true

    public function createActivityController() {
        if ($this->test == False) {
            return new ActivityController();
        } else {
            return new MockActivityController();
        }
    }

    public function createAthleteController() {
        if ($this->test == False) {
            return new AthleteController();
        } else {
            return new MockAthleteController();
        }
    }

    public function createAuthController() {
        if ($this->test == False) {
            return new AuthController();
        } else {
            return new MockAuthController();
        }
    }

    public function createStravaAPIController() {
        if ($this->test == False) {
            return new StravaAPIController();
        } else {
            return new MockStravaAPIController();
        }
    }
}