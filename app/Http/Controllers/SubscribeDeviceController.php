<?php

namespace App\Http\Controllers;

use App\UserNotificationToken;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SubscribeDeviceController extends Controller
{
    public function subscribe(Request $request)
    {
        $userNotification = UserNotificationToken::query()
            ->where('key', Auth::user()->id)
            ->where('value', $request->token)
            ->firstOrCreate([
                'key' => Auth::user()->id,
                'value' => $request->token,
            ]);

        if (!$userNotification) {
            throw new HttpResponseException(response('null', 400));
        }

        return response(null);
    }

}
