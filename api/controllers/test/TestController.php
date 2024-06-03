<?php

use helpers\CheckProtectedAttribute;

class TestController {

  #[CheckProtectedAttribute('false')]
  public function get($args) {
    return json_encode($args);
  }

  #[CheckProtectedAttribute('false')]
  public function post(): false|string {
    $data = json_decode(file_get_contents("php://input"));
    return json_encode($data);
  }

}
