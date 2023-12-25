<?php

use helpers\JwtHelpers;
use helpers\RoutingHelpers;

$root = realpath($_SERVER["DOCUMENT_ROOT"]);

include_once 'TestController.php';
include_once "$root/api/lib/vendor/autoload.php";
include_once "$root/api/lib/helpers/JwtHelpers.php";
include_once "$root/api/lib/helpers/RoutingHelpers.php";

$testController = new TestController();
$jwtHelpers = new JwtHelpers();

$method = $_REQUEST['method'];
$controller = $_REQUEST['controller'];
foreach (RoutingHelpers::getControllerMethods(ucfirst($controller) . 'Controller') as $item) {
  echo $item->getName() . '<br>';
}

//switch ($method) {
//  case 'get':
//    error_reporting(~0);
//    ini_set('display_errors', 1);
//    $testController->get();
//    break;
//  default:
//    echo RoutingHelpers::set404();
//    break;
//}
