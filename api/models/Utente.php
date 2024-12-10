<?php

namespace models;

use Illuminate\Database\Eloquent\Model;

class Utente extends Model {
  protected $table = 'mp_utenti';
  protected $primaryKey = 'u_id';
  public $incrementing = true;
  public $timestamps = false;

  protected $fillable = [
      "u_id",
      "u_email",
      "u_password",
      "u_ruolo",
      "u_attivo",
      "u_data_login",
      "u_data_aggiunta",
      "u_data_modifica",
  ];
}