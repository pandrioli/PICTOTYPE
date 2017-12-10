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
    private $notifyman; // Notification Manager, inyectado en el constructor

    public function __construct(NotificationManager $notifyman) {
      $this->notifyman = $notifyman; // asigna al atributo $notifyman el NotificationManager inyectado
    }

    // pantalla de perfil
    public function profile() {
        $user = Auth::user();
        return view('profile', compact('user'));
    }

    // guardado de los datos enviados por el formulario de perfil
    public function update(Request $request) {
        $user = Auth::user(); // usuario actual
        $user->fill($request->all()); // llena los datos recibidos
        $user->save(); // guarda
        $file = $request->file('avatar'); // archivo de imagen
        if ($file) { // si hay archivo de imagen
          $extension = pathinfo($file->getClientOriginalName(), PATHINFO_EXTENSION); // obtengo la extension del archivo
          $path = '/public/avatars/'.$user->id.'.'.$extension; // genero el path al archivo
          Storage::put($path,
                      file_get_contents($file->getRealPath())
                  ); // guardo el archivo de imagen
          $user->avatar = $path; // seteo el avatar con el path a la imagen guardada
          $user->save(); // guardo el usuario
        }
        return redirect()->route('profile'); // vuelvo a mi perfil
    }

    // cambiar colores
    public function changeTheme() {
      $user = Auth::user();
      $user->theme = $user->theme == 0 ? 1 : 0;
      $user->save();
      return redirect()->route('home');
    }

    // pagina de amigos / buscar usuarios
    // si recibe un juego es porque es para crear una partida privada
    public function friendsPage($game = null) {
      $user = Auth::user();
      return view('friends', compact('user', 'game'));
    }

    // ver detalles de usuario, recibe $back que es a donde llevaria el boton "volver"
    public function viewUser($id, $back) {
      $user = User::find($id); // instancia usuario
      return view('view_user', compact('user', 'back')); // mostrar vista view_user enviando la variable que conteine al usuario y a donde va "volver"
    }

    //busqueda de usuarios (por nick, nombre o apellido al mismo tiempo)
    public function searchUsers($query) {
      $users= User::where(function ($usr) use ($query) {
        $usr->where('username', 'like', $query.'%')
          ->orWhere('first_name', 'like', $query.'%')
          ->orWhere('last_name', 'like', $query.'%');
      })->where('id', '<>', Auth::user()->id)->get();
      return view('includes/user_items', compact('users')); // devuelve un html con la lista de usuarios encontrados
    }

    // solicitar amistad... $back es a donde vuelve el boton "volver"
    public function friendshipRequest($id, $back) {
      $user = Auth::user(); // usuario actual
      $friend = User::find($id); // usuario al que se le pide amistad
      $user->friends()->attach($friend, ['accepted' => 0]); // agrega al usuario requerido a la tabla de amigos pero con el accepted en 0
      $this->notifyman->notifyFriendshipRequest($id); // notifica el pedido de amistad
      return $this->getRoute($friend->id, $back); // obtiene la ruta a mostrar mediante la funcion privada getRoute
    }

    // aceptar amistad
    public function friendshipAccept($id, $back) {
      $user = Auth::user(); //usuario actual
      $friend = User::find($id); // usuario que pidio la amistad
      $user->friends()->attach($friend, ['accepted' => 1]); // se agrega al usuario actual el amigo
      $friend->friends()->updateExistingPivot($user->id, ['accepted' => 1]); // se pone como aceptada la amistad en el amigo
      $this->notifyman->notifyFriendshipAccepted($id); // se notifica que se acepto la amistad
      return $this->getRoute($friend->id, $back); // obtiene la ruta a mostrar mediante la funcion privada getRoute
    }

    // cancelar amistad
    public function friendshipCancel($id, $back) {
      $user = Auth::user(); // usuario actual
      $friend = User::find($id); // usuario que pidio amistad
      $user->friends()->detach($friend); // se saca al amigo del usuario actual
      $friend->friends()->detach($user); // se saca al usuario actual del amigo
      $this->notifyman->notifyFriendshipCancelled($id); // se notifica que se cancelo la amistad
      return $this->getRoute($friend->id, $back); // obtiene la ruta blablabla
    }

    // obtener la ruta segun de donde viene el asunto
    private function getRoute($id, $back) {
      if ($back=="home") return redirect()->route('home'); // se ingreso desde las notificaciones, volver a home
      else return redirect()->route('viewuser', array($id, $back)); // se ingreso desde otro lado, dejar la pantalla de view user
    }
}
