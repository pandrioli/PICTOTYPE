@php
  Use App\Notification;
@endphp

@foreach ($notifications as $key => $notification)
  @php
    $href = "";
    if ($notification->type == Notification::NOTIFY_GAME) {
      $href = "/game/view/".$notification->game_id;
    }
    if ($notification->type == Notification::NOTIFY_FRIENDSHIP) {
      $href = "/user/view/".$notification->sender_id."/home";
    }
  @endphp
  <{{ $notification->clickeable ? 'a': 'div' }}
    class='notification-item {{ $notification->read? '':'notification-new'}}'
    href="{{ $href }}"
    onclick="notifRead(this)"
  >
    <div hidden>{{ $notification->read}}</div>
    <div hidden>{{ $notification->id}}</div>
    {!! $notification->message !!}
  <{{ $notification->clickeable ? '/a': '/div' }}>
@endforeach
