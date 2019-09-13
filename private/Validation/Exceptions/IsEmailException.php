<?php
namespace App\Validation\Exceptions;

use \Respect\Validation\Exceptions\ValidationException;

class IsEmailException extends ValidationException {

  public static $defaultTemplates = [
    self::MODE_DEFAULT => [
      self::STANDARD => 'Помилка у e-mail адресі',
    ],
  ];

}