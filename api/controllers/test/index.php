<?php

//error_reporting(~0);
//ini_set('display_errors', 1);

use helpers\RoutingHelpers;

include_once 'TestController.php';
include_once __DIR__ . "/lib/vendor/autoload.php";
include_once __DIR__ . "/lib/helpers/RoutingHelpers.php";

$testController = new TestController();

$method = $_REQUEST['method'];
$controller = $_REQUEST['controller'];
$controllerNome = ucfirst($controller) . 'Controller';

echo RoutingHelpers::getRoute($controllerNome, $method);