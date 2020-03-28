<?php
namespace App\Models;

use \Illuminate\Database\Eloquent\Model;

class Task extends Model {
  protected $table = 'tasks';

  protected $fillable = [
    'section_id',
    'subject_id', 
    'teacher_id', 
    'grades',
    'title',
    'content',
    'is_actual',
    'user_id',
  ];

  public function section() {
    return $this->belongsTo('App\Models\Section', 'section_id'); 
  }

  public function subject() {
    return $this->belongsTo('App\Models\Subject', 'subject_id'); 
  }

  public function teacher() {
    return $this->belongsTo('App\Models\Teacher', 'teacher_id'); 
  }

  public function user() {
    return $this->belongsTo('App\Models\User', 'user_id'); 
  }

  public static function tasks($id) {
    return self::query()
      ->join('sections', 'tasks.section_id', '=', 'sections.id')
      ->join('subjects', 'tasks.subject_id', '=', 'subjects.id')
      ->join('teachers', 'tasks.teacher_id', '=', 'teachers.id')
      ->orderBy('id', 'DESC')
      ->where('tasks.section_id', $id)
      ->get([
        'tasks.*',
        'sections.title as section_title',
        'subjects.title as subject_title',
        'teachers.full_name as teacher_name',
      ]);
   }

}