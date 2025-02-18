<?php

use database\EloquentInit;
use helpers\CheckProtectedAttribute;
use Illuminate\Support\Facades\DB;

class TestController {

  public function __construct() {

    $ei = new EloquentInit();
    $ei->init();
  }

  #[CheckProtectedAttribute('false')]
  public function get($args) {
//    $users = [];
    $users = DB::table('persone')->get();
//    $users = DB::getConnection()->select("SELECT * FROM persone");

    return json_encode($users);
  }

  #[CheckProtectedAttribute('false')]
  public function post(): false|string {
    $data = json_decode(file_get_contents("php://input"));
    return json_encode($data);
  }

}
