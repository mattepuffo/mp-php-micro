<?php

use helpers\JwtHelpers;

$root = realpath($_SERVER["DOCUMENT_ROOT"]);

include_once 'TestController.php';
include_once "$root/api/lib/vendor/autoload.php";
include_once "$root/api/lib/helpers/JwtHelpers.php";
include_once "$root/api/lib/helpers/RoutingHelpers.php";

$testController = new TestController();
$jwtHelpers = new JwtHelpers();

$method = $_REQUEST['method'];
$controller = $_REQUEST['controller'];
$controllerNome = ucfirst($controller) . 'Controller';

$reflector = new \ReflectionClass($controllerNome);
$methods = $reflector->getMethods();
foreach ($methods as $item) {
  foreach ($item->getAttributes() as $attr) {
    $reflectionMethod = new ReflectionMethod($controllerNome, $item->getName());

    if ($method == $item->getName()) {
      foreach ($attr->getArguments() as $arg) {
        if ($arg === 'true') {
          $checkToken = $jwtHelpers->checkToken($_SERVER['HTTP_AUTHORIZATION']);
          if (!$checkToken) {
            echo $jwtHelpers->erroMessage();
            exit();
          }
        }

        echo $reflectionMethod->invoke($reflector->newInstance());
      }
    }
  }
}