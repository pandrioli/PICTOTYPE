<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Auth;
use Storage;
use App\User;

class UserController extends Controller
{
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

    public function viewUser($id) {
      $user = User::find($id);
      return view('view_user', compact('user'));
    }

    public function searchUsers($query) {
      $users= User::where(function ($usr) use ($query) {
        $usr->where('username', 'like', $query.'%')
          ->orWhere('first_name', 'like', $query.'%')
          ->orWhere('last_name', 'like', $query.'%');
      })->get();
      return view('user_items', compact('users'));
    }

}
