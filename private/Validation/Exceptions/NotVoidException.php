<?php
namespace App\Validation\Exceptions;

use \Respect\Validation\Exceptions\ValidationException;
 
class NotVoidException extends ValidationException {
  
  public static $defaultTemplates = [
    self::MODE_DEFAULT => [
      self::STANDARD => 'Порожні дані',
    ],
 ];

}