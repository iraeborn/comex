<?php

namespace App;


use App\Helpers\Utilidade;
use Illuminate\Database\Eloquent\Model;

class LogEmail extends Model
{
  CONST STATUS_PENDING = 0;
  CONST STATUS_SENDING = 1;
  CONST STATUS_SUCCESS = 2;
  CONST STATUS_ERROR = 3;

  protected $table = 'log_email';

  protected $fillable = ['id','user_id','status','from_name','from_email'];
}
