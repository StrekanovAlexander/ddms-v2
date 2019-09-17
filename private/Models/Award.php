<?php
namespace App\Models;

use \Illuminate\Database\Eloquent\Model;

class Award extends Model {
    protected $table = 'awards';

    protected $fillable = [
      'section_id',
      'title',
      'content',
      'is_actual',
      'user_id',
    ];

    public static function awards() {
      return self::query()
        ->join('sections', 'awards.section_id', '=', 'sections.id')
        ->orderBy('id', 'DESC')
        ->get([
          'awards.*',
          'sections.title as section_title',
        ]);
    }

    public function section() {
      return $this->belongsTo('App\Models\Section', 'section_id');
    }

    public function user() {
      return $this->belongsTo('App\Models\User', 'user_id');
    }
    

}