<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Bank extends Model
{

    protected $table = "banks";

    protected $fillable = [
        'id',
    ];

    // public function __toString()
    // {
    //     $text = "<b>INTERMEDIARY BANK: $this->intermediary_name </b><br/>/ $this->local <br/>";

    //     if ($this->account_nr_one) {
    //         $text .= "ACCOUNT NR: $this->account_nr_one <br />";
    //     }

    //     $text .= "SWIFT CODE: $this->swift_code <br />
    //         <b>BENEFICIARY BANK: $this->beneficiary_bank </b><br />
    //         IBAN: $this->iban <br />";

    //     if ($this->swift_code_two) {
    //         $text .= "SWIFT CODE: $this->swift_code_two <br />";
    //     }

    //     $text .= "<b>BENEFICIARY NAME: $this->beneficiary_name </b><br />
    //         $this->beneficiary_bank <br />
    //         BRANCH NUMBER: $this->branch_number <br />";

    //     if ($this->account_nr_two) {
    //         $text .= "ACCOUNT NR: $this->account_nr_two  <br />";
    //     }

    //     $text .= "*all banker commission expenses will be the importer's responsibilities";

    //     return $text;
    // }

    public function user()
    {
        return $this->hasOne(\App\User::class, 'id', 'user_id');
    }
}
