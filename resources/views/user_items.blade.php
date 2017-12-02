@foreach ($users as $other_user)
  <a id='user-{{ $other_user->id }}' class='user-item' href="/user/{{$other_user->id}}">
    <style>
      #user-avatar-{{ $other_user->id }} {
        background: url({{$other_user->avatar}});
        background-position: center;
        background-size: cover;
        background-repeat: no-repeat;
      }
    </style>
    <div id="user-avatar-{{ $other_user->id }}" class='game-item-avatar'></div>
    <div class="user-item-username">
      {{ $other_user->username }}
    </div>
    <div class="user-item-fullname">
      {{ $other_user->fullName() }}
    </div>
  </a>
@endforeach
