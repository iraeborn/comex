<?php

namespace App;

use App\Helpers\Utilidade;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Role extends Model
{
    use SoftDeletes;
    
    public function users () {
        return $this->belongsToMany('App\Users', 'users_roles');
    }

}
