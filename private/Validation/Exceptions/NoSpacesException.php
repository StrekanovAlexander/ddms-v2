<?php
namespace App\Validation\Exceptions;

use \Respect\Validation\Exceptions\ValidationException;

class NoSpacesException extends ValidationException {

  public static $defaultTemplates = [
    self::MODE_DEFAULT => [
      self::STANDARD => 'It not must contains spaces',
    ]
  ]; 

} 