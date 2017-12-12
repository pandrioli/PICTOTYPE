<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Phrase;

class AdminController extends Controller
{
    //

    public function getPhrases() {
      $phrases = Phrase::all();
      return view('admin', compact('phrases'));
    }

    public function setPhrases(Request $request) {
      $cant = intVal($request->input('cantidad'));
      for ($i=1; $i <= $cant ; $i++) {
        $phrase = Phrase::find($i);
        if ($phrase) {
          $phrase->phrase = strtoupper($request->input((string)$i));
          $phrase->save();
        }
        else {
          $phrase = new Phrase();
          $phrase->phrase = strtoupper($request->input((string)$i));
          $phrase->save();
        }
      }
      return redirect()->route('admin');
    }
}
