<?php

namespace database;
class Connessione {

  private $pdo;
  private $userDb;
  private $passwordDb;
  private $hostDb;
  private $nameDb;

  private static $instance;

  private function __construct() {
    $this->userDb = getenv('DB_USER');
    $this->passwordDb = getenv('DB_PASSWORD');
    $this->hostDb = getenv('DB_HOST');
    $this->nameDb = getenv('DB_NAME');

    try {
      $this->pdo = new PDO("mysql:host=" . $this->hostDb . ";dbname=" . $this->nameDb, $this->userDb, $this->passwordDb, array(
          PDO::ATTR_PERSISTENT => TRUE,
          PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
          PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'
      ));

      $this->pdo->exec("SET SESSION sql_mode=''");
    } catch (PDOException $e) {
      return $e->getMessage();
    }
  }

  public static function getInstance() {
    if (!isset(self::$instance)) {
      $c = __CLASS__;
      self::$instance = new $c;
    }
    return self::$instance;
  }

  public function __clone() {
    trigger_error('Clone is not allowed', E_USER_ERROR);
  }

  public function execQuery($cmd) {
    try {
      $result = $this->pdo->query($cmd);
      return $result;
    } catch (PDOException $e) {
      return $e->getMessage();
    }
  }

  public function execQueryPrepare($cmd, $arrayCampi) {
    $prepare = $this->pdo->prepare($cmd);
    $prepare->execute($arrayCampi);
    return $prepare->fetchAll(PDO::FETCH_ASSOC);
  }

  public function execPrepare($cmd, $arrayCampi) {
    $prepare = $this->pdo->prepare($cmd);
    $prepare->execute($arrayCampi);
  }

  public function execPrepareLastId($cmd, $arrayCampi) {
    $prepare = $this->pdo->prepare($cmd);
    $prepare->execute($arrayCampi);
    return $this->pdo->lastInsertId();
  }

  public function execQueryLogin($cmd, $arrayCampi) {
    $cmd = $this->pdo->prepare($cmd);
    $cmd->execute($arrayCampi);
    if ($cmd->rowCount() == 1) {
      return $cmd->fetchAll();
    } else {
      return FALSE;
    }
  }

}
