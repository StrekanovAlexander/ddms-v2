<?php
namespace App\Validation\Rules;

use \Respect\Validation\Rules\AbstractRule;

class Compare extends AbstractRule {
  
  protected $value;
  
  public function __construct($value) {
    $this->value = $value;
  }

  public function validate($input) {
    return trim($input) === trim($this->value);
  }
  
} 