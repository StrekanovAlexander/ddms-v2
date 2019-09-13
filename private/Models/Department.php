<?php
namespace App\Models;

use \Illuminate\Database\Eloquent\Model;

class Department extends Model {

  protected $table = 'departments';

  public function section() {
    return $this->belongsTo('\App\Models\Section', 'section_id');
  }

}