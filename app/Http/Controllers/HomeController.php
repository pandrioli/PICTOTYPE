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


    public function home()
    {
        if (!Auth::check()) return view('home');
        $user = Auth::user();
        $timestamp = date_create();
        $timestamp = $timestamp->format('Y-m-d H:i:s');
        return view('home_user', compact('user','timestamp'));
    }
}
