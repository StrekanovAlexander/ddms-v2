<?php
namespace App\Validation\Exceptions;

use \Respect\Validation\Exceptions\ValidationException;
 
class CompareException extends ValidationException {
  
  public static $defaultTemplates = [
    self::MODE_DEFAULT => [
      self::STANDARD => 'Дані не співпадають',
    ],
 ];

}