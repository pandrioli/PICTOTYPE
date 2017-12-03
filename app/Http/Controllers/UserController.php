<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Auth;
use Storage;
use App\User;
use App\CustomClasses\NotificationManager;

class UserController extends Controller
{
    private $notifyman;

    public function __construct(NotificationManager $notifyman) {
      $this->notifyman = $notifyman;
    }

    public function profile() {
        $user = Auth::user();
        return view('profile', compact('user'));
    }

    public function update(Request $request) {
        $user = Auth::user();
        $user->fill($request->all());
        $user->save();
        $file = $request->file('avatar');
        if ($file) {
          $extension = pathinfo($file->getClientOriginalName(), PATHINFO_EXTENSION);
          $path = '/public/avatars/'.$user->id.'.'.$extension;
          Storage::put($path,
                      file_get_contents($file->getRealPath())
                  );
          $user->avatar = $path;
          $user->save();
        }
        return redirect()->route('profile');
    }

    public function changeTheme() {
      $user = Auth::user();
      $user->theme = $user->theme == 0 ? 1 : 0;
      $user->save();
      return redirect()->route('home');
    }

    public function friendsPage($game = null) {
      $user = Auth::user();
      return view('friends', compact('user', 'game'));
    }

    public function viewUser($id, $back) {
      $user = User::find($id);
      return view('view_user', compact('user', 'back'));
    }

    public function searchUsers($query) {
      $users= User::where(function ($usr) use ($query) {
        $usr->where('username', 'like', $query.'%')
          ->orWhere('first_name', 'like', $query.'%')
          ->orWhere('last_name', 'like', $query.'%');
      })->where('id', '<>', Auth::user()->id)->get();
      return view('includes/user_items', compact('users'));
    }

    public function friendshipRequest($id, $back) {
      $user = Auth::user();
      $friend = User::find($id);
      $user->friends()->attach($friend, ['accepted' => 0]);
      $this->notifyman->notifyFriendshipRequest($id);
      return $this->getRoute($friend->id, $back);
    }
    public function friendshipAccept($id, $back) {
      $user = Auth::user();
      $friend = User::find($id);
      $user->friends()->attach($friend, ['accepted' => 1]);
      $friend->friends()->updateExistingPivot($user->id, ['accepted' => 1]);
      $this->notifyman->notifyFriendshipAccepted($id);
      return $this->getRoute($friend->id, $back);
    }
    public function friendshipCancel($id, $back) {
      $user = Auth::user();
      $friend = User::find($id);
      $user->friends()->detach($friend);
      $friend->friends()->detach($user);
      $this->notifyman->notifyFriendshipCancelled($id);
      return $this->getRoute($friend->id, $back);
    }
    private function getRoute($id, $back) {
      if ($back=="home") return redirect()->route('home');
      else return redirect()->route('viewuser', array($id, $back));
    }
}
