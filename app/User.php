<?php

namespace App;

use App\Helpers\Utilidade;

use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;
    //use SoftDeletes;

    const DEFAULT_AVATAR = '/img/avatar.png';

    protected $fillable = [
        'name', 'email', 'password',
    ];

    protected $hidden = [
        'password', 'token',
    ];

    private static $userLogged;

    public function contacts()
    {
        return $this->hasMany(\App\Contact::class);
    }

    public function orders()
    {
        return $this->hasMany(\App\Order::class, 'owner_id', 'id');
    }

    public function group()
    {
        return $this->hasOne(\App\Group::class, 'id', 'group_id');
    }

    public function contracts()
    {
        return $this->hasMany(\App\ProviderContract::class, 'provider_id', 'id');
    }

    public function banks () {
        return $this->hasOne(\App\Bank::class, 'entity_id', 'id');
    }
    public function roles () {
        return $this->belongsToMany(\App\Role::class, 'users_roles');
    }

    public function brokerOrders () {
        return $this->hasMany(\App\Order::class, 'broker_id', 'id');
    }

    public function certificateOfFumigationOrders () {
        return $this->hasMany(\App\Order::class, 'fumigation_id', 'id');
    }

    public function certificateOfQualityOrders () {
        return $this->hasMany(\App\Order::class, 'quality_id', 'id');
    }

    // Todo remover o weight_id da tabela de orders (Parou de ser usado)
    public function certificateOfWeightOrders () {
        return $this->hasMany(\App\Order::class, 'weight_id', 'id');
    }

    public function certificateOfSeguroOrders () {
        // ToDo: Troca o nome do campo
        return $this->hasMany(\App\Order::class, 'weight_id', 'id');
    }

    public function inspectionAgency () {
        return $this->hasMany(\App\Order::class, 'inspection_id', 'id');
    }

    public function mapa () {
        return $this->hasMany(\App\Order::class, 'forwarding_agent_id', 'id');
    }

    public function hasRole ( $role_name ) {
        foreach ($this->roles()->get() as $role) {
            if($role->name == $role_name) return true;
        }

        return false;
    }

    public function getCountryAttribute($value){
        return Utilidade::upperCase($value);
    }

    public function getNameAttribute($value){
        return Utilidade::upperCase($value);
      }
      
    public function setChildrenAttribute($value){
        $this->attributes['children'] = ($value ? implode(',',$value) : null);
    }

    public function getChildrenAttribute($value){

        if(!$value)return [];

        return explode(',',$value);
    }

    private function valid($field) {
        return $field && $field != '.';
    }

    // T: <phone> / Email ID: <email>
    public function contactString ($alt = false) {
        $string = '';

        $phone = $this->contacts()->where('contact_type_id', 4)->first();
        if ($phone) {
            $phone = $phone->value;
        }

        $email = $this->contacts()->where('contact_type_id', 2)->first();
        if ($email) {
            $email = $email->value;
        }

        if ($this->valid($phone)) {
            $string .= ($alt ? 'T: ' : '') . $phone;
        }

        if ($this->valid($phone) && $this->valid($email)) {
            $string .= ' / ';
        }

        if ($this->valid($email)) {
            $string .= ($alt ? 'Email ID: ' : '') . $email;
        }

        if ($string) {
            $string .= '<br/>';
        }

        return $string;
    }

    /*
    <address>, <number> - <neighborhood>
    <zip>
    <city> - <state> - <country>
    */
    public function addressString () {
        $string = '';

        if ($this->valid($this->address_1)) {
            $string .= $this->address_1;
            if ($this->valid($this->number)) {
                $string .= ', ' . $this->number;
            }
        }

        if ($this->valid($this->address_1) && $this->valid($this->neighborhood)) {
            $string .= ' - ';
        }

        if ($this->valid($this->neighborhood)) {
            $string .= $this->neighborhood;
        }

        if ($this->valid($this->address_1) || $this->valid($this->neighborhood)) {
            $string .= '<br/>';
        }
        
        if ($this->valid($this->zip)) {
            $string .= $this->zip . '<br/>';
        }

        if ($this->valid($this->city)) {
            $string .= $this->city;
        }

        if ($this->valid($this->city) && $this->valid($this->state)) {
            $string .= ' - ';
        }

        if ($this->valid($this->state)) {
            $string .= $this->state;
        }

        if (($this->valid($this->city) || $this->valid($this->state)) && $this->valid($this->country)) {
            $string .= ' - ';
        }

        if ($this->valid($this->country)) {
            $string .= $this->country;
        }

        if ($string) {
            $string .= '<br/>';
        }

        return $string;
    }

    function regString ($alt = false) {
        $string = '';

        if ($this->valid($this->cnpj)) {
            $string .= ($alt ? 'COMPANY VAT NUMBER: ' : '') . $this->cnpj;
        }

        if (!$alt) {
            if ($this->valid($this->cnpj) && $this->valid($this->state_registration)) {
                $string .= ' - ';
            }

            if ($this->valid($this->state_registration)) {
                $string .= $this->state_registration;
            }
        }

        if ($string) {
            $string .= '<br/>';
        }

        return $string;
    }

    public static function setUserLogged($userLogged){
        self::$userLogged = $userLogged;
        
        if($userLogged->group){
            \Config::set('app.name', $userLogged->group->name);
            $userLogged->group->setEmailConfig();
        }
    }

    public static function getUserLogged(){
        return self::$userLogged;
    }
}
