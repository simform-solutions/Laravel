<?php

namespace App\Listeners;

use Carbon\Carbon;

class UserEventSubscriber
{
    /**
     * Create the event listener.
     *
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle user login events.
     * @param $event
     */
    public function onUserLogin($event) {
        if ('api' === \request()->route()->getPrefix() && !\request()->hasHeader('Authorization')) {
            $event->user->fill([
                'device_type' => \request()->header('Device-Type'),
                'time_zone'  => \request()->header('Time-Zone'),
                'last_login_at' => Carbon::now()
            ]);
            $event->user->save();
        }
    }

    /**
     * Register the listeners for the subscriber.
     *
     * @param  \Illuminate\Events\Dispatcher  $events
     */
    public function subscribe($events)
    {
        $events->listen(
            'Illuminate\Auth\Events\Login',
            'App\Listeners\UserEventSubscriber@onUserLogin'
        );
    }
}
