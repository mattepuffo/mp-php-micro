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

sendCorsHeaders();

include_once __DIR__ . "/lib/vendor/autoload.php";
include_once __DIR__ . "/lib/config/MyEnv.php";
include_once __DIR__ . "/lib/helpers/RoutingHelpers.php";
include_once __DIR__ . "/lib/helpers/JwtHelpers.php";

(new MyEnv(__DIR__ . '/.env'))->load();

$filename = __DIR__ . preg_replace('#(\?.*)$#', '', $_SERVER['REQUEST_URI']);
if (php_sapi_name() === 'cli-server' && is_file($filename)) {
  return false;
}

$jwtHelpers = new JwtHelpers();
$controller = $_REQUEST['controller'];
$is404 = true;
foreach (RoutingHelpers::getControllers() as $item) {
  if ($item == $controller) {
    $is404 = false;
    include_once "controllers/$item/index.php";
    break;
  }
}

if ($is404) {
  echo RoutingHelpers::set404();
}