<?php
namespace App\Models;

use \Illuminate\Database\Eloquent\Model;

class Teacher extends Model {

  protected $table = 'teachers';

  protected $fillable = [
    'department_id',
    'full_name',
    'content',
    'rank',
    'phones',
    'is_remote',
    'is_actual',
    'user_id',
  ];

  public function department() {
    return $this->belongsTo('App\Models\Department', 'department_id'); 
  }

  public function user() {
    return $this->belongsTo('App\Models\User', 'user_id');
  }

  public static function teachers() {
    return self::query()
      ->join('departments', 'teachers.department_id', '=', 'departments.id')
      ->join('sections', 'departments.section_id', '=', 'sections.id')
      ->orderBy('rank')
      ->get([
        'teachers.*',
        'departments.title as department_title',
        'sections.title as section_title',
       ]);
  }

  public static function remote($section_id) {
    return self::query()
      ->join('departments', 'teachers.department_id', '=', 'departments.id')
      ->join('sections', 'departments.section_id', '=', 'sections.id')
      ->orderBy('teachers.full_name')
      ->where('teachers.is_remote', 1)
      ->where('sections.id', $section_id)
      ->get([
        'teachers.*',
       ]);
  }

}