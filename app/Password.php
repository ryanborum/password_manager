<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Password extends Model
{
  protected $table = 'passwords';

  protected $guarded = ['id'];
  protected $dates = ['expiration_date'];
  protected $appends = array('DaysUntilExpiration');

  public function user(){
    return $this->belongsTo('App\User', 'user_id');
  }

  public function getDaysUntilExpirationAttribute(){
    if ($this->expiration_date){
      return \Carbon\Carbon::today()->diffInDays($this->expiration_date, false);
    }
    else{
      return "X";
    }
  }

  public function getExpirationColorAttribute(){
    if ( !is_numeric($this->DaysUntilExpiration) ){
      return 'black';
    }
    if ($this->DaysUntilExpiration < 2){
      return '#c11515'; //dark-red
    }
    elseif ($this->DaysUntilExpiration <= 5){
      return '#e08f2a'; //orange
    }
    elseif ($this->DaysUntilExpiration <= 10){
      return '#f1dc27'; //yellow
    }
    else{
      return 'black';
    }
  }
}
