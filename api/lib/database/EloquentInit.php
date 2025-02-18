<?php

namespace database;

use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Support\Facades\DB;

class EloquentInit {

  public function init(): void {
    $capsule = new Capsule;

    $capsule->addConnection([
        'driver' => 'mysql',
        'host' => getenv('DB_HOST'),
        'database' => getenv('DB_NAME'),
        'username' => getenv('DB_USER'),
        'password' => getenv('DB_PASSWORD'),
        'charset' => 'utf8',
        'collation' => 'utf8_unicode_ci',
        'prefix' => '',
    ]);

    $capsule->setAsGlobal();
    $capsule->bootEloquent();

    DB::setFacadeApplication([
        'db' => $capsule,
    ]);

  }

}