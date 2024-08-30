<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Mail extends Model
{
    protected $fillable = [
        'port',
        'driver',
        'host',
        'encryption',
        'username',
        'password',
        'name'
    ];
}
