<?php

namespace App;
use Illuminate\Database\Eloquent\SoftDeletes;

use Illuminate\Database\Eloquent\Model;

class Railroad extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'withdrawal_date',
        'start_date',
        'arrival_date',
        'final_date',
        'withdrawal_start_date',
        'estimated_time',
        'loading_location',
        'transfer_terminal_date',
        'terminal_confirmation_date',
        'freetime_date',
    ];

    protected $dates = [
        'withdrawal_date',
        'start_date',
        'arrival_date',
        'final_date',
        'withdrawal_start_date',
        'estimated_time',
        'transfer_terminal_date',
        'terminal_confirmation_date',
        'freetime_date',
    ];

    public function getArrivalDateAttribute($date) {
        if (!$date) return '';
        return \Carbon\Carbon::parse($date)->format('Y-m-d');
    }

    public function getEstimatedTimeAttribute($date) {
        if (!$date) return '';
        return \Carbon\Carbon::parse($date)->format('Y-m-d');
    }

    public function getFinalDateAttribute($date) {
        if (!$date) return '';
        return \Carbon\Carbon::parse($date)->format('Y-m-d');
    }

    public function getStartDateAttribute($date) {
        if (!$date) return '';
        return \Carbon\Carbon::parse($date)->format('Y-m-d');
    }

    public function getWithdrawalDateAttribute($date) {
        if (!$date) return '';
        return \Carbon\Carbon::parse($date)->format('Y-m-d');
    }

    public function getWithdrawalStartDateAttribute($date) {
        if (!$date) return '';
        return \Carbon\Carbon::parse($date)->format('Y-m-d');
    }

    public function getTransferTerminalDateAttribute($date) {
        if (!$date) return '';
        return \Carbon\Carbon::parse($date)->format('Y-m-d');
    }

    public function getTerminalConfirmationDateAttribute($date) {
        if (!$date) return '';
        return \Carbon\Carbon::parse($date)->format('Y-m-d');
    }

    public function getFreetimeDateAttribute($date) {
        if (!$date) return '';
        return \Carbon\Carbon::parse($date)->format('Y-m-d');
    }

    public function observations () {
        return $this->hasMany(\App\RailwayObservation::class, 'order_id', 'order_id');
    }
    
}
