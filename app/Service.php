<?php

namespace App;

use App\Helpers\Utilidade;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    protected $table = 'services';

    protected $fillable = [
        'id',
        'name',
        'active',
    ];

    public function getNameAttribute($value) {
        return Utilidade::upperCase(Utilidade::removeSpecialChar($value));
    }
}
