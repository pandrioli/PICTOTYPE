@php
  Use App\Notification;
  $href = "";
  if ($notification->clickeable) {
    if ($notification->type == Notification::NOTIFY_GAME) {
      $href = "/viewgame/".$notification->game_id;
    }
  }
@endphp
<a id='notification-{{ $notification->id }}' class='notification-item {{ $notification->read? '':'notification-new'}}' href="{{ $href }}">
  {!! $notification->message !!}
</a>
