<?php

namespace App;


use App\Helpers\Utilidade;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Crypt;

class Group extends Model
{
  protected $guarded = ['id'];

  protected $fillable = ['name', 'smtp'];

  public function getNameAttribute($value)
  {
    return Utilidade::upperCase($value);
  }

  public function getSmtpAttribute($value)
  {

    if (!$value) {
      return [
        'from_name' => null,
        'from_email' => null,
        'user' => null,
        'password' => null,
        'host' => null,
        'port' => 587,
        'encryption' => 'tls',
        'authentication' => 1,
      ];
    }

    $value = json_decode($value, true);

    if (isset($value['password'])) {
      $value['password'] = Crypt::decrypt($value['password']);
    }

    return $value;
  }

  public function setSmtpAttribute($value)
  {

    if (isset($value['password'])) {
      $value['password'] = Crypt::encrypt($value['password']);
    }

    $this->attributes['smtp'] = ($value ? json_encode($value) : null);
  }

  public function setEmailConfig($arrayConfig = [])
  {

    $config = array(
      'driver'     => 'smtp',
      'host'       => (isset($arrayConfig['smtp']['host']) ? $arrayConfig['smtp']['host'] : $this->smtp['host']),
      'port'       => (isset($arrayConfig['smtp']['port']) ? $arrayConfig['smtp']['port'] : $this->smtp['port']),
      'from'       => array('address' => (isset($arrayConfig['smtp']['from_email']) ? $arrayConfig['smtp']['from_email'] : $this->smtp['from_email']), 'name' => (isset($arrayConfig['smtp']['from_name']) ? $arrayConfig['smtp']['from_name'] : $this->smtp['from_name'])),
      'encryption' => (isset($arrayConfig['smtp']['encryption']) ? $arrayConfig['smtp']['encryption'] : $this->smtp['encryption']),
      'username'   => (isset($arrayConfig['smtp']['user']) ? $arrayConfig['smtp']['user'] : $this->smtp['user']),
      'password'   => (isset($arrayConfig['smtp']['password']) ? $arrayConfig['smtp']['password'] : $this->smtp['password']),
      'pretend'    => false,
    );
    Config::set('mail', $config);
  }
}
