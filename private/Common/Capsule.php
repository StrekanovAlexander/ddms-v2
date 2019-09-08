<?php
namespace App\Common;

class Capsule {

  public static function capsule($db) {
    $capsule = new \Illuminate\Database\Capsule\Manager;
    $capsule->addConnection($db);
    $capsule->setAsGlobal();
    $capsule->bootEloquent();
    return $capsule;
  }

}