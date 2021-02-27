<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserNotificationToken extends Model
{
    public $table = 'user_notification_token';
    
    protected $fillable = [
        'key', 'value'
    ];

    public $timestamps = false;
}
