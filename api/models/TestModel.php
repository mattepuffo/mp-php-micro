<?php

namespace models;

use Illuminate\Database\Eloquent\Model;

class TestModel extends Model {
  protected $table = 'test';
  protected $primaryKey = 'id';
  public $incrementing = true;
  public $timestamps = false;

  protected $fillable = [
      "id",
      "data_aggiunta",
      "data_modifica",
  ];
}