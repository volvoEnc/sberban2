<?php

namespace App\Http\Controllers;

use App\Notifications\PushNotification;
use Illuminate\Http\Request;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;

class NotifyUserController extends Controller
{
    use Notifiable;
    
    public function push(Request $request)
    {
        $this->notify(new PushNotification);
        return response(null);
    }

    /**
     * @param $notification
     * @return string
     */
    public function routeNotificationForExpoPushNotifications($notification): string
    {
        return Auth::user()->id;
    }
    
}
