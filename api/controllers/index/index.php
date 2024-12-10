<?php

use helpers\RoutingHelpers;

include_once 'IndexController.php';

$controller = new IndexController();

$method = $_REQUEST['method'];
$controller = $_REQUEST['controller'];
$controllerNome = ucfirst($controller) . 'Controller';

echo RoutingHelpers::getRoute($controllerNome, $method);