<?php
namespace App\Models;

use \Illuminate\Database\Eloquent\Model;

class Student extends Model {
  protected $table = 'students';

  public function department() {
    return $this->belongsTo('App\Models\Section', 'section_id'); 
  }

}