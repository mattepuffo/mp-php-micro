<?php

namespace helpers;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class JwtHelpers {

  public function createToken(): string {
    $issuedAt = time();
    $expire = $issuedAt + 60 * 60 * 24 * 60;

    $token = array(
        "iss" => getenv('SERVER_NAME'),
        "aud" => getenv('AUDIENCE'),
        "iat" => $issuedAt,
        "nbf" => $issuedAt,
        "exp" => $expire,
    );

    return JWT::encode($token, getenv('GLOBAL_SECRET_KEY'), 'HS256');
  }

  public function checkToken($authHeader): bool {
    $arr = explode(" ", $authHeader);
    $jwt = $arr[1];

    try {
      if ($jwt) {
        $token = JWT::decode($jwt, new Key(getenv('GLOBAL_SECRET_KEY'), 'HS256'));
        $now = new \DateTimeImmutable();

        if ($token->iss !== getenv('SERVER_NAME') || $token->nbf > $now->getTimestamp() || $token->exp < $now->getTimestamp()) {
          return false;
        } else {
          return true;
        }
      } else {
        return false;
      }
    } catch (\Exception $e) {
      return false;
    }

  }

  public function erroMessage(): false|string {
    return json_encode(array(
        "res" => "ko",
        "message" => "Accesso negato",
        "error" => "Nessun token"
    ));
  }

}