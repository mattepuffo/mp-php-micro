<?php

namespace helpers;

class RoutingHelpers {

  public static function getControllers() {
    $root = realpath($_SERVER["DOCUMENT_ROOT"]);
    $dir = "$root/api/controllers/";
    $dirs = scandir($dir);
    $controllers = array();
    foreach ($dirs as $controller) {
      if ($controller != '..' && $controller != '.' && is_dir($dir . $controller)) {
        $controllers[] = $controller;
      }
    }
    return $controllers;
  }

  public static function set404() {
    header('HTTP/1.1 404 Not Found');
    header('Content-Type: application/json');

    $jsonArray = array();
    $jsonArray['status'] = "ko";
    $jsonArray['message'] = "route not defined";

    return json_encode($jsonArray);
  }

}
