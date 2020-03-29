<?php
namespace App\Models;

use \Illuminate\Database\Eloquent\Model;

class User extends Model {

  protected $table = 'users';

  protected $fillable = [
    'username',
    'password',
    'is_actual',
    'is_teacher',
  ];

  public static function add($array) {
    User::create([
      'username' => $array['username'],
      'password' => password_hash($array['password'], PASSWORD_DEFAULT),
      'is_teacher' => $array['is_teacher'] ? true : false,
      'is_actual' => $array['is_actual'] ? true : false,
    ]);
  }

  public static function setPassword($password) {
    return password_hash($password, PASSWORD_DEFAULT);
  }

} 