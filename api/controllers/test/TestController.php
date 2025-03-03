<?php

use database\EloquentInit;
use helpers\CheckProtectedAttribute;
use helpers\JwtHelpers;

class TestController {
  private JwtHelpers $jwtHelpers;

  public function __construct() {
    $this->jwtHelpers = new JwtHelpers();

    $ei = new EloquentInit();
    $ei->init();
  }

  #[CheckProtectedAttribute('true')]
  public function get($args): false|string {
    $users = [];
//    $users = DB::table('persone')->get();
//    $users = DB::getConnection()->select("SELECT * FROM persone");

    return json_encode($users);
  }

  #[CheckProtectedAttribute('false')]
  public function post(): false|string {
//    $data = json_decode(file_get_contents("php://input"));
//    return json_encode($data);

    return json_encode(array(
        "res" => "ok",
        "message" => "Login eseguito correttamente",
        "jwt" => $this->jwtHelpers->createToken(),
    ));
  }

}
