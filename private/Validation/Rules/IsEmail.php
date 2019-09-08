<?php
namespace App\Validation\Rules;

use \Respect\Validation\Rules\AbstractRule;

class IsEmail extends AbstractRule {

  public function validate($input) {
    return (preg_match("/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/i", $input));
  }

}