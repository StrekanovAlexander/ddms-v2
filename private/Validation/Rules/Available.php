<?php
namespace App\Validation\Rules;

use \Respect\Validation\Rules\AbstractRule;

class Available extends AbstractRule {

  private $field;
  private $class;

  public function __construct($field, $class) {
    $this->field = $field;
    $this->class = new $class;
  }

  public function validate($input) {
    return $this->class->where($this->field, trim($input))->count() === 0;
  }

}