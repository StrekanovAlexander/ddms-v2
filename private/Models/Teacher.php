<?php
namespace App\Models;

use \Illuminate\Database\Eloquent\Model;

class Teacher extends Model {

  protected $table = 'teachers';

  public function department() {
    return $this->belongsTo('App\Models\Department', 'department_id'); 
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

}