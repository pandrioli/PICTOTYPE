@php
  Use App\Notification;
@endphp

@foreach ($notifications as $key => $notification)
  @php
    $href = "";
    if ($notification->clickeable) {
      if ($notification->type == Notification::NOTIFY_GAME) {
        $href = "/viewgame/".$notification->game_id;
      }
    }
  @endphp
  <a class='notification-item {{ $notification->read? '':'notification-new'}}' href="{{ $href }}">
    <div hidden>{{ $notification->read}}</div>
    {!! $notification->message !!}
  </a>
@endforeach
