<?php

namespace App\Providers;

use App\Events\ActivityEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class UpdateActivityTitle
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
     * @param  \App\Events\ActivityEvent  $event
     * @return void
     */
    public function handle(ActivityEvent $event)
    {
        //
    }
}
