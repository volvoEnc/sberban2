<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Endpoint extends Model
{
    protected $fillable = [
        'name', 'uri', 'user_id'
    ];

    protected $table = 'endpoints';
    public $timestamps = false;
}
