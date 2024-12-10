<?php

//error_reporting(~0);
//ini_set('display_errors', 1);

use helpers\CheckProtectedAttribute;

class IndexController {

  public function __construct() {}

  #[CheckProtectedAttribute('true')]
  public function get($args): false|string {
    return json_encode(array('Versione framework' => getenv('FRAMEWORK_VERSION')));
  }

}