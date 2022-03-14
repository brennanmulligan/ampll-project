<?php

namespace App\Providers;

use App\Events\AthleteEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class UpdateAthleteAuthRevoked
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  \App\Events\AthleteEvent  $event
     * @return void
     */
    public function handle(AthleteEvent $event)
    {
        //
    }
}
