<?php

use config\MyEnv;
use helpers\JwtHelpers;
use helpers\RoutingHelpers;

function sendCorsHeaders() {
  header("Access-Control-Allow-Origin: *");
  header("Content-Type: application/json; charset=UTF-8");
  header("Access-Control-Allow-Methods: *");
  header("Access-Control-Max-Age: 3600");
  header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
}

//sendCorsHeaders();

$root = realpath($_SERVER["DOCUMENT_ROOT"]);

include_once "$root/api/lib/vendor/autoload.php";
include_once "$root/api/lib/config/MyEnv.php";
include_once "$root/api/lib/helpers/RoutingHelpers.php";
include_once "$root/api/lib/helpers/JwtHelpers.php";

(new MyEnv(__DIR__ . '/.env'))->load();

$filename = __DIR__ . preg_replace('#(\?.*)$#', '', $_SERVER['REQUEST_URI']);
if (php_sapi_name() === 'cli-server' && is_file($filename)) {
  return false;
}

$jwtHelpers = new JwtHelpers();

$controller = $_REQUEST['controller'];
foreach (RoutingHelpers::getControllers() as $item) {
  if ($item == $controller) {
    include_once "controller/$item/index.php";
    break;
  }

  echo RoutingHelpers::set404();
}

//switch ($controller) {
//  case 'dashboard':
//    $checkToken = $jwtHelpers->checkToken($_SERVER['HTTP_AUTHORIZATION']);
//    if (!$checkToken) {
//      echo $jwtHelpers->erroMessage();
//      exit();
//    }
//    include_once 'controller/dashboard/index.php';
//    break;
//  case 'clienti':
//    $checkToken = $jwtHelpers->checkToken($_SERVER['HTTP_AUTHORIZATION']);
//    if (!$checkToken) {
//      echo $jwtHelpers->erroMessage();
//      exit();
//    }
//    include_once 'controller/clienti/index.php';
//    break;
//  case 'ordini':
//    $checkToken = $jwtHelpers->checkToken($_SERVER['HTTP_AUTHORIZATION']);
//    if (!$checkToken) {
//      echo $jwtHelpers->erroMessage();
//      exit();
//    }
//    include_once 'controller/ordini/index.php';
//    break;
//  case 'pagamenti':
////    error_reporting(~0);
////    ini_set('display_errors', 1);
//    $checkToken = $jwtHelpers->checkToken($_SERVER['HTTP_AUTHORIZATION']);
//    if (!$checkToken) {
//      echo $jwtHelpers->erroMessage();
//      exit();
//    }
//    include_once 'controller/pagamenti/index.php';
//    break;
//  case 'prodotti':
//    $checkToken = $jwtHelpers->checkToken($_SERVER['HTTP_AUTHORIZATION']);
//    if (!$checkToken) {
//      echo $jwtHelpers->erroMessage();
//      exit();
//    }
//    include_once 'controller/prodotti/index.php';
//    break;
//  case 'utenti':
//    include_once 'controller/utenti/index.php';
//    break;
//  case 'test':
//    include_once 'controller/test/index.php';
//    break;
//  default:
//    echo RoutingHelpers::set404();
//    break;
//}
