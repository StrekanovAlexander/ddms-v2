<?php
namespace App\Models;

use \Illuminate\Database\Eloquent\Model;

class User extends Model {

  protected $table = 'users';

  protected $fillable = [
    'username',
    'password',
    'email',
  ];

  public static function add($array) {
    User::create([
      'username' => $array['username'],
      'password' => password_hash($array['password'], PASSWORD_DEFAULT),
      'email' => $array['email'],
    ]);
  }

} 