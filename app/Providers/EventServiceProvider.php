<?php

namespace App\Providers;

use Illuminate\Auth\Events\Registered;
use App\Events\ActivityEvent;
use App\Events\AthleteEvent;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class
        ],
        ActivityEvent::class => [
            CreateActivity::class,
            DeleteActivity::class,
            UpdateActivityTitle::class,
            UpdateActivityType::class,
            UpdateActivityPrivacy::class,
        ],
        AthleteEvent::class => [
            UpdateAthleteAuthRevoked::class
        ]
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
