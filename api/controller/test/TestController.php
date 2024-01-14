<?php

use helpers\CheckProtectedAttribute;

class TestController {

  #[CheckProtectedAttribute('true')]
  public function get() {
    echo 'ciao!';
  }

  #[CheckProtectedAttribute('false')]
  public function free() {
    $array = array(
        array(
            'nome' => 'matteo',
            'sport' => array('calcio', 'arrampicata', 'sci')
        )
    );

    return json_encode($array);
  }

}
