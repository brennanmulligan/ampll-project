<?php

namespace Tests\Browser;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Symfony\Component\Console\Logger\ConsoleLogger;
use Symfony\Component\Console\Output\ConsoleOutput;
use Tests\DuskTestCase;

class CalendarTest extends DuskTestCase
{
    /**
     * A Dusk test example.
     *
     * @return void
     */
    public function testLoadCalendar()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/')
                    ->select("athlete_id", "100470297")
                    ->click('input[type="submit"]')
                    ->assertSee("Calendar");
        });
    }

    public function testSwitchMonth() {
        $this->browse(function (Browser $browser) {
            $months = array(
                1 => "January",     2 => "February",       3 => "March",
                4 => "April",       5 => "May",            6 => "June",
                7 => "July",        8 => "August",         9 => "September",
                10 => "October",    11 => "November",      12 => "December"
            );

            $monthVal = intval(date("m"));
            $prevMonth = $monthVal - 1 < 1 ? $months[12] : $months[$monthVal - 1];
            $nextMonth = $monthVal + 1 > 12 ? $months[1] : $months[$monthVal + 1];

            $changeMonth = function($goForward) use (&$monthVal, $months) {
                if ($goForward) {
                    $monthVal = $monthVal == 12 ? 1 : $monthVal + 1;
                } else {
                    $monthVal = $monthVal == 1 ? 12 : $monthVal - 1;
                }

                return $months[$monthVal];
            };

            $browser->visit('/calendar?athlete_id=100470297')
                    ->assertSee("Calendar")
                    ->click("button[id=prev]")
                    ->waitForText($changeMonth(false))
                    ->assertSee($prevMonth)
                    ->click("button[id=next]")
                    ->waitForText($changeMonth(true))
                    ->click("button[id=next]")
                    ->waitForText($changeMonth(true))
                    ->assertSee($nextMonth);
        });
    }

    public function testOpenEvent() {
        $this->browse(function (Browser $browser) {
            $browser->visit("/calendar?athlete_id=98764319");

            $months = array(
                1 => "January",     2 => "February",       3 => "March",
                4 => "April",       5 => "May",            6 => "June",
                7 => "July",        8 => "August",         9 => "September",
                10 => "October",    11 => "November",      12 => "December"
            );
            $monthVal = intval(date("m"));

            $changeMonth = function($goForward) use (&$monthVal, $months) {
                if ($goForward) {
                    $monthVal = $monthVal == 12 ? 1 : $monthVal + 1;
                } else {
                    $monthVal = $monthVal == 1 ? 12 : $monthVal - 1;
                }

                return $months[$monthVal];
            };

            $hasEvents = false;
            while (!$hasEvents) {
                if ($browser->element(".event")) {
                    $hasEvents = true;
                } else {
                    $browser->click("button[id=prev]")
                            ->waitForText($changeMonth(false));
                }

                $calendarMonth = $browser->script("return document.getElementById('header').innerHTML;");

                if (implode($calendarMonth) == "January 2010") {
                    self::fail("Error: User has no events");
                }
            }

            $browser->script('document.getElementsByClassName("event")[0].click();');

            $browser->pause(100)
                    ->assertSee("Private (Strava)");
        });
    }
}
