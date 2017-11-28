<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
  const NOTIFY_GENERIC = 0;
  const NOTIFY_GAME = 1;
  const NOTIFY_INVITATION = 2;
  const NOTIFY_FRIENDSHIP = 3;
  const NOTIFY_ADMIN_MESSAGE = 100;
}
