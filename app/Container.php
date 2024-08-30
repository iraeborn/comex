<?php

namespace App;
use Illuminate\Database\Eloquent\SoftDeletes;

use Illuminate\Database\Eloquent\Model;

class Container extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'name',
        'tare',
        'seal',
        'cbm',
        'release_date',
        'shipping_id',
        'withdrawal_date',
        'return_date'
    ];

    protected $dates = [
        'withdrawal_date',
        'return_date'
    ];

    public function getTareAttribute()
    {
        $tare = $this->attributes['tare'];
    
        if (is_numeric($tare)) {
            return number_format($tare / 100, 2, '.', ',');
        }
    
        return '0';
    }

    public function setTareAttribute($value)
    {
        $value = floatval(preg_replace('/[^\d.]+/', '', $value));
        $this->attributes['tare'] = $value * 100;
    }

    /*public function getSealAttribute()
    {
        return number_format($this->attributes['seal'] / 100, 2, '.', ',');
    }

    public function setSealAttribute($value)
    {
        $value = floatval(preg_replace('/[^\d.]+/', '', $value));
        $this->attributes['seal'] = $value * 100;
    }*/

    public function shipping ()
    {
        return $this->belongsTo(Shipping::class);
    }

    public function bills ()
    {
        return $this->hasMany(Bill::class);
    }

    public function getWithdrawalDateAttribute ( $date ) {
        if(!$date) return null;
        return \Carbon\Carbon::parse($date)->format('Y-m-d');
    }

    public function getReturnDateAttribute ( $date ) {
        if(!$date) return null;
        return \Carbon\Carbon::parse($date)->format('Y-m-d');
    }

}
