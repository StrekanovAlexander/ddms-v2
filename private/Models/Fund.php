<?php
namespace App\Models;

use \Illuminate\Database\Eloquent\Model;

class Fund extends Model {
    protected $table = 'fund';

    protected $fillable = [
      'section_id',
      'title',
      'content',
      'is_actual',
      'user_id',
    ];

    public static function fund() {
      return self::query()
        ->join('sections', 'fund.section_id', '=', 'sections.id')
        ->orderBy('id', 'DESC')
        ->get([
          'fund.*',
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