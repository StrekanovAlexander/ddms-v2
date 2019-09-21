<?php
namespace App\Models;

use \Illuminate\Database\Eloquent\Model;

class Audio extends Model {

  protected $table = 'audio';

  protected $fillable = [
    'title',
    'content',
    'is_actual',
    'user_id',
  ];

  public function user() {
    return $this->belongsTo('App\Models\User', 'user_id');
  }

}