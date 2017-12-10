<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Game;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */

    // controlador que simplemente muestra el home correspondiente a usuario logueado o no
    public function home()
    {
        if (!Auth::check()) return view('home'); // no esta logueado, muestra el home con el slide de imagenes
        $user = Auth::user();
        $timestamp = date_create();
        $timestamp = $timestamp->format('Y-m-d H:i:s'); // timestamp que sirve para el tema de las actualizaciones con ajax
        return view('home_user', compact('user','timestamp')); // esta logueado, muestra el home para usuarios
    }
}
