<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
  // constantes tipo de notificacion
  const NOTIFY_GENERIC = 0; // notificacion generica
  const NOTIFY_GAME = 1; // notificacion acerca de partidas
  const NOTIFY_FRIENDSHIP = 2; // notificacion acerca de amistades
  const NOTIFY_ADMIN_MESSAGE = 100; // notificacion del administrador
}
