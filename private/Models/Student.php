<?php
namespace App\Models;

use \Illuminate\Database\Eloquent\Model;

class Student extends Model {
  protected $table = 'students';

  protected $fillable = [
    'section_id',
    'full_name',
    'content',
    'final_year',
    'slug',
    'is_actual',
    'user_id',
  ];

  public function section() {
    return $this->belongsTo('App\Models\Section', 'section_id'); 
  }

  public function user() {
    return $this->belongsTo('App\Models\User', 'user_id'); 
  }

  public static function students() {
    return self::query()
      ->join('sections', 'students.section_id', '=', 'sections.id')
      ->orderBy('id', 'DESC')
      ->get([
        'students.*',
        'sections.title as section_title',
      ]);
  }

}