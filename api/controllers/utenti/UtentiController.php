<?php

//error_reporting(~0);
//ini_set('display_errors', 1);

use database\EloquentInit;
use helpers\CheckProtectedAttribute;
use helpers\JwtHelpers;
use Illuminate\Database\QueryException;
use models\Utente;

class UtentiController {
  private JwtHelpers $jwtHelpers;

  public function __construct() {
    $this->jwtHelpers = new JwtHelpers();

    $ei = new EloquentInit();
    $ei->init();
  }

  #[CheckProtectedAttribute('true')]
  public function get($args): false|string {
    try {
      $query = Utente::query();

      if ($id = $args['id'] ?? null) {
        $query->where('u_id', $id);
      }

      return json_encode(array(
          'data' => $query->get()
      ));
    } catch (QueryException $ex) {
      return json_encode(array(
          'data' => $ex->getMessage()
      ));
    }
  }

  #[CheckProtectedAttribute('false')]
  public function addOrUpdate(): false|string {
    $data = json_decode(file_get_contents("php://input"), true);

    if (!empty($data)) {
      $id = $data['u_id'];
      $today = date("Y-m-d H:i:s");

      $arrData = array(
          "u_email" => trim(strtolower($data['u_email'])),
          "u_password" => trim($data['u_password']),
          "u_ruolo" => $data['u_ruolo'],
          "u_attivo" => trim($data['u_attivo']),
          "u_data_login" => null,
          "u_data_aggiunta" => $today,
          "u_data_modifica" => $today
      );

      if ($id == 0) {
        $query = Utente::create($arrData);
        $lastId = $query->u_id;
      } else {
        Utente::where('u_id', '=', $id)
            ->update($arrData);
        $lastId = $id;
      }

      return json_encode(array(
          "res" => "ok",
          "message" => "Operazione avvenuta con successo!",
          "id" => $lastId
      ));
    } else {
      return json_encode(array(
          "res" => "ko",
          "message" => "Dati mancanti!"
      ));
    }
  }

  #[CheckProtectedAttribute('false')]
  public function login(): false|string {
    $data = json_decode(file_get_contents("php://input"), true);

    if (!empty($data)) {
      $today = date("Y-m-d H:i:s");
      $email = trim($data['u_email']);
      $password = $data['u_password'];

      return json_encode(array(
          "res" => "ok",
          "message" => "Login eseguito correttamente",
          "jwt" => $this->jwtHelpers->createToken(),
      ));

//      $query = Utente::query()
//          ->where('u_email', $email)
//          ->where('u_password', $password)
//          ->where('u_attivo', 1)
//          ->first();

//      if ($query) {
//        Utente::where('u_id', $query->u_id)
//            ->update(['u_data_login' => $today]);
//
//        return json_encode(array(
//            "res" => "ok",
//            "message" => "Login eseguito correttamente",
//            "jwt" => $this->jwtHelpers->createToken(),
//            "utente" => $query,
//        ));
//
//      } else {
//        return json_encode(array(
//            "res" => "ko",
//            "message" => "Credenziali errate",
//        ));
//      }

    } else {
      return json_encode(array(
          "res" => "ko",
          "message" => "Dati mancanti",
      ));
    }
  }

}