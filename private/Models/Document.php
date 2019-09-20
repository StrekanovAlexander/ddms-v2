<?php
namespace App\Models;

use \Illuminate\Database\Eloquent\Model;

class Document extends Model {
  
  protected $table = 'documents';

  protected $fillable = [
    'title',
    'src',
    'is_remote',
    'is_actual',
  ];

  public function user() {
    return $this->belongsTo('App\Models\User', 'user_id');
  }
  
}