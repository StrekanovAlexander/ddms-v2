<?php
namespace App\Validation\Rules;

use \Respect\Validation\Rules\AbstractRule;

class NotVoid extends AbstractRule {

  public function validate($input) {
    return strlen(trim($input));
  }

} 