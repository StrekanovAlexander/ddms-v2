<?php
namespace App\Common;

use \Illuminate\Pagination\LengthAwarePaginator;

class Pages {

  const HOME_PAGE = ['Головна', 'home'];
  const AUTH_HOME_PAGE = ['Головна', 'admin.dashboard'];
  const BREADCRUMB_KEYS = [0 => 'title', 1 => 'route', 2 => 'param'];
  
  public static function pagination(array $arr, $page, $perPage) {
    return new LengthAwarePaginator(
      array_slice($arr, ($page - 1) * $perPage, $perPage),
      sizeof($arr),
      $perPage,
      $page
    );
  }
  
  public static function breadcrumbs(array $arr = [], $isAuth = false) {
    if ($isAuth) {
      array_unshift($arr, self::AUTH_HOME_PAGE);
    } else {
      array_unshift($arr, self::HOME_PAGE);
    }
    $result = [];
    foreach($arr as $value) {
      $elem = [];
      foreach(self::BREADCRUMB_KEYS as $index => $field) {
        if (isset($value[$index])) {
          $elem[$field] = $value[$index];
        }
      }
      $result[] = $elem;
    } 
    return $result;
  }
}