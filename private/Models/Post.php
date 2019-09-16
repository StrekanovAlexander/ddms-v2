<?php
namespace App\Models;

use \Illuminate\Database\Eloquent\Model;

class Post extends Model {
  protected $table = 'posts';

  public function section() {
    return $this->belongsTo('App\Models\Section', 'section_id'); 
  }

}