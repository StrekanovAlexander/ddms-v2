<?php
namespace App\Validation\Exceptions;

use \Respect\Validation\Exceptions\ValidationException;

class IsEmailException extends ValidationException {

  public static $defaultTemplates = [
    self::MODE_DEFAULT => [
      self::STANDARD => 'It`s not valid email',
    ],
  ];

}