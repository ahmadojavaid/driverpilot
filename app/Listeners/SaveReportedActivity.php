<?php

namespace App\Listeners;

use App\Events\ActivityWasNoted;
use App\User;
use App\UserActivity;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;

class SaveReportedActivity
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
     * @param  ActivityWasNoted  $event
     * @return void
     */
    public function handle(ActivityWasNoted $event)
    {
        $data = $event->notification;
        $user = User::find(auth()->user()->id);
        $activity = new UserActivity();
        $activity->name = $data['name'];
        $activity->time = $data['time'];
        $activity->mod_id = $data['mod_id'];
        $user->user_activities()->save($activity);
    }
}
