<?php


namespace App;


use Illuminate\Database\Eloquent\Model;

class Signatures extends Model
{

    protected $table = 'signatures';

    protected $fillable = [
        'url',
        'text'
    ];

    public $timestamps = false;

    public function user () {
        return $this->hasOne(\App\User::class, 'id', 'user_id');
    }

    public function getTerm1Attribute($value) {
        if(!$value) return null;
        return base64_decode($value);
    }

    public function setTerm1Attribute($value) {
        if($value){
            $this->attributes['term1'] = base64_encode($value);
        }
    }

    public function getTerm2Attribute($value) {
        if(!$value) return null;
        return base64_decode($value);
    }

    public function setTerm2Attribute($value) {
        if($value){
            $this->attributes['term2'] = base64_encode($value);
        }
    }
}
