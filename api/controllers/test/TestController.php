<?php

use helpers\CheckProtectedAttribute;

class TestController {

  #[CheckProtectedAttribute('true')]
  public function blocked() {
    $array = array(
        array(
            'key1' => 'val1',
            'key2' => array('val2', 'val3')
        )
    );

    return json_encode($array);
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
