<?php

namespace helpers;

class RoutingHelpers {

  public static function getControllers(): array {
    $root = realpath($_SERVER["DOCUMENT_ROOT"]);
    $dir = "$root/" . getenv('CONTROLLERS_DIR');
    $dirs = scandir($dir);
    $controllers = array();
    foreach ($dirs as $controller) {
      if ($controller != '..' && $controller != '.' && is_dir($dir . $controller)) {
        $controllers[] = $controller;
      }
    }
    return $controllers;
  }

  public static function cleanController($nome): string {
    $separator = str_contains($nome, '-') ? '-' : '_';
    $explode = explode($separator, $nome);
    $nome = '';
    foreach ($explode as $part) {
      $nome .= ucfirst($part);
    }
    return $nome . 'Controller';
  }

  public static function getRoute($controllerNome, $method) {
    $jwtHelpers = new JwtHelpers();
    $is404 = true;

    $reflector = new \ReflectionClass($controllerNome);
    $methods = $reflector->getMethods();
    foreach ($methods as $item) {
      foreach ($item->getAttributes() as $attr) {
        $reflectionMethod = new \ReflectionMethod($controllerNome, $item->getName());

        if ($method == $item->getName()) {
          $is404 = false;
          foreach ($attr->getArguments() as $arg) {
            if ($arg === 'true') {
              $checkToken = $jwtHelpers->checkToken($_SERVER['HTTP_AUTHORIZATION']);
              if (!$checkToken) {
                return $jwtHelpers->erroMessage();
//                exit();
              }
            }

            return $reflectionMethod->invoke($reflector->newInstance(), $_REQUEST);
          }
        }
      }
    }

    if ($is404) {
      return RoutingHelpers::set404();
    }
  }

  public static function set404(): false|string {
    header('HTTP/1.1 404 Not Found');
    header('Content-Type: application/json');

    $jsonArray = array();
    $jsonArray['status'] = "ko";
    $jsonArray['message'] = "route not defined";

    return json_encode($jsonArray);
  }

}
