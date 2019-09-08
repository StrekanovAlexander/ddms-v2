<?php
namespace App\Validation\Rules;

use \Respect\Validation\Rules\AbstractRule;

class NoSpaces extends AbstractRule {

  public function validate($input) {
    return !strpos($input, ' ');
  }

}