<?php
namespace App\Models;

use \Illuminate\Database\Eloquent\Model;

class Teacher extends Model {

  protected $table = 'teachers';

  public function department() {
    return $this->belongsTo('App\Models\Department', 'department_id'); 
  }

}