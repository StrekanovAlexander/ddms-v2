<?php
namespace App\Models;

use \Illuminate\Database\Eloquent\Model;

class Subject extends Model {

  protected $table = 'subjects';

  protected $fillable = [
    'section_id',
    'title',
    'is_actual',
  ];

  public function section() {
    return $this->belongsTo('App\Models\Section', 'section_id'); 
  }

}