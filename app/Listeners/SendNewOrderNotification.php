<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendNewOrderNotification
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }
    public function handle($event)
    {
        $admins = User::where('role', 'admin')
            ->get();

        Notification::send($admins, new NewOrderNotification($event->order));
    }
    
}
