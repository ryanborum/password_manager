<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LoginRecord extends Model
{
  protected $table = 'login_history';

  protected $guarded = ['id'];

  public function user(){
    return $this->belongsTo('App\Models\User', 'user_id');
  }
}
