<?php

use helpers\JwtHelpers;
use helpers\RoutingHelpers;

include_once 'IndexController.php';

$jwtHelpers = new JwtHelpers();
$controller = new IndexController();

$method = $_REQUEST['method'];
$controller = $_REQUEST['controller'];
$controllerNome = RoutingHelpers::cleanController($controller);
$is404 = true;

$reflector = new \ReflectionClass($controllerNome);
$methods = $reflector->getMethods();

foreach ($methods as $item) {
  foreach ($item->getAttributes() as $attr) {
    $reflectionMethod = new ReflectionMethod($controllerNome, $item->getName());

    if ($method == $item->getName()) {
      $is404 = false;
      foreach ($attr->getArguments() as $arg) {
        if ($arg === 'true') {
          $checkToken = $jwtHelpers->checkToken($_SERVER['HTTP_AUTHORIZATION']);
          if (!$checkToken) {
            echo $jwtHelpers->erroMessage();
            exit();
          }
        }

        echo $reflectionMethod->invoke($reflector->newInstance(), $_REQUEST);
      }
    }
  }
}

if ($is404) {
  echo RoutingHelpers::set404();
}