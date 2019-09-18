<?php
namespace App\Models;

use \Illuminate\Database\Eloquent\Model;

class Post extends Model {
  protected $table = 'posts';

  public function section() {
    return $this->belongsTo('App\Models\Section', 'section_id'); 
  }

  public function user() {
    return $this->belongsTo('App\Models\User', 'user_id'); 
  }

  public static function posts() {
    return self::query()
      ->join('sections', 'posts.section_id', '=', 'sections.id')
      ->orderBy('id', 'DESC')
      ->get([
        'posts.*',
        'sections.title as section_title',
       ]);
  }

}