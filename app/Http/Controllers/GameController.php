<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Game;
use App\Phrase;
use App\Word;
use App\User;
use App\CustomClasses\NotificationManager;

class GameController extends Controller
{
    private $notifyman;
    private $words;
    public function __construct(NotificationManager $notifyman) {
      $this->notifyman = $notifyman;
      $words = [];
      $files = glob(public_path()."/img/pictotypes/*.jpg");
      foreach ($files as $file) {
        $words[] = pathinfo($file)['filename'];
      }
      $this->words = $words;
    }


    public function tutorial() {
      $game = $this->generateGame(3, 20, false);
      $user_id = 0;
      return view('gameplay', compact('game','user_id'));
    }

    public function publicGame() {
      return view('public_game');
    }

    public function joinPublicGame($mode) {
      $public_game = DB::table('games')->join('games_users', 'games.id', '=', 'games_users.game_id')
        ->where([['games_users.user_id', '<>', Auth::user()->id], ['games.state', 1], ['games.mode', $mode]])
        ->select('games.id as id')
        ->first();
      $game = Game::find($public_game->id);
      $this->addPlayer($game->id, Auth::user());
      if ($game) return redirect()->route('gameplay', $game->id);
      else return redirect()->route('publicgame');
    }

    public function acceptGame($game_id, $user_id) {
      $this->addPlayer($game_id, Auth::user());
      $this->notifyman->notifyGameAccepted($game_id, $user_id);
      return redirect()->route('gameplay', $game_id);
    }

    public function createGameForm($data) {
      $practique = $data == "practique" ? true : false;
      $user_id = $data == "public" ? 0 : $data;
      return view('create_game', compact('practique', 'user_id'));
    }

    public function createGame(Request $request) {
      $owner = Auth::user();
      $mode = $request->input('mode');
      $practique = $request->input('practique');
      $user_id = intval($request->input('user_id'));
      $time_per_letter = $request->input('time-per-letter');
      $play_now = $practique || $request->input('play_now');
      $game = $this->generateGame($mode, $time_per_letter, $practique);
      if (!$practique) $this->addPlayer($game->id, $owner);
      if ($user_id) {
        $user = User::find($user_id);
        if ($owner->friendship($user_id) == User::FRIENDSHIP_ACCEPTED) {
          $this->addPlayer($game->id, $user);
          $this->notifyman->notifyGameCreated($game->id, $user->id);
        } else {
          $this->notifyman->notifyGameInvitation($game->id, $user->id);
          $game->state = Game::STATE_INVITATION;
          $game->invitation_id = $user->id;
          $game->save();
        }
      }
      if ($play_now) return redirect()->route('gameplay', $game->id);
      else return redirect()->route('home');
    }

    public function viewGame($id) {
      $game = Game::find($id);
      $user = $game->currentPlayer();
      if ($user || $game->invitation_id == Auth::user()->id) return view('view_game', compact('game'));
      else return redirect()->route('home');
    }

    public function playGame($id) {
      $game = Game::find($id);
      if (!$game) return redirect()->route('home');
      $game->load('players');
      $user_id = Auth::check() ? Auth::user()->id : '';
      if (!$game->practique && $game->getPlayerState() != Game::PLAYER_READY) {
        if ($game->getPlayerState() == Game::PLAYER_PLAYING) {
          $game->state = Game::STATE_CANCELLED;
          $game->save();
        }
        return redirect()->route('home');
      }
      if (!$game->practique) $game->setPlayerState(Game::PLAYER_PLAYING);
      return view('gameplay', compact('game','user_id'));
    }

    public function finishGame(Request $request) {
      if (!$request->input('user_id')) return redirect()->route('home');
      $game = Game::find($request->input('game_id'));
      $game->load('players');
      $game->setPlayerState(Game::PLAYER_DONE);
      $game->setPlayerTime($request->input('time'));
      $game->setPlayerPoints($request->input('points'));
      $opponent = $game->opponentPlayer();
      if ($opponent && $game->getOpponentState()==Game::PLAYER_DONE && $game->state != Game::STATE_CANCELLED) {
        $win = 0;
        if ($game->mode == 0) {
          if ($game->getPlayerTime() < $game->getOpponentTime()) $win = 1;
          if ($game->getPlayerTime() > $game->getOpponentTime()) $win = 2;
        }
        if ($game->mode == 1) {
          if ($game->getPlayerPoints() > $game->getOpponentPoints()) $win = 1;
          if ($game->getPlayerPoints() < $game->getOpponentPoints()) $win = 2;
        }
        if ($win == 1) {
          $game->winner_id = $game->currentPlayer()->id;
        }
        if ($win == 2) {
          $game->winner_id = $game->opponentPlayer()->id;
        }
        if ($win>0) $game->state = Game::STATE_FINISHED; //gano alguien
        else { // empate - volver a jugar - winner_id 0 indica empate
          $game->state = Game::STATE_WAITING_PLAYS;
          $game->setPlayerState(Game::PLAYER_READY);
          $game->setOpponentState(Game::PLAYER_READY);
          $game->winner_id = 0;
        }
        $game->save();
        $this->notifyman->notifyGameFinished($game);
      }
      return redirect()->route('viewgame', $game->id);
    }

    public function cancelGame($game_id, $user_id) {
      $game = Game::find($game_id);
      if ($game->currentPlayer()->id == $user_id) {
        $game->state = Game::STATE_CANCELLED;
        $game->setPlayerState(Game::PLAYER_DONE);
        if ($game->opponentPlayer()) {
          $game->setOpponentState(Game::PLAYER_DONE);
          $this->notifyman->notifyGameCancelled($game);
        }
        $game->save();
      }
      return redirect()->route('home');
    }

    private function addPlayer($game_id, $user) {
      $game = Game::find($game_id);
      $game->players()->attach($user);
      if ($game->opponentPlayer()) {
        $game->state = Game::STATE_WAITING_PLAYS;
      }
      $game->save();
    }

    private function generateGame($mode, $time = 0, $practique = false) {
      $game = new Game();
      $phrase = Phrase::inRandomOrder()->first();
      $game->practique = $practique;
      $game->phrase_id = $phrase->id;
      $game->mode = $mode;
      $game->time_per_letter = $time;
      $game->state = Game::STATE_WAITING_OPPONENT;
      $game->save();
      $words = $this->getGameWords($phrase->phrase);
      foreach ($words as $word) {
        $game_word = new Word();
        $game_word->word = $word;
        $game_word->game_id = $game->id;
        $game_word->save();
      }
      return $game;
    }

    private function getGameWords($phrase) {
      $word_chain = ''; // aca va a ir una cadena concatenada de todas las palabras elegidas
      $phrase = str_replace("Ñ", "_", $phrase); // reemplazo la Ñ (traia problemas)
      $phrase = strtolower($phrase); // paso a minusculas
      $phrase = str_replace(" ", "", $phrase); // saco los espacios
      $required_words = []; // array con las palabras requeridas
      $words = $this->words; // variable temporal que contiene la coleccion de palabras
      shuffle($words); // mezclo las palabras (para que todas tengan chances de ser elegidas y para generar azar en las de relleno)
      $letters = array_unique(str_split($phrase));  // obtengo las letras necesarias
      shuffle($letters); // mezclo las letras
      $word_key = 0; // variable para guardar la key de la palabra elegida
      foreach ($letters as $letter) { // para cada letra
        if (strpos($word_chain, $letter)===FALSE) { // si la letra no esta en la lista de palabras ya elegidas (concatenadas)
          foreach ($words as $key => $word) { // para cada palabra de la coleccion
            if (strpos($word, $letter) !== FALSE) { // si la palabra contiene la letra que se necesita
              $required_words[] = $word; // agregar la palabra al array de las palabras requeridas
              $word_chain = $word_chain.$word; // agregar la palabra a la concatenacion
              $word_key = $key; // guardo la key de la palabra elegida
              break; // chau
            }
          }
          array_splice($words,$word_key,1); // saco del array la palabra que ya use
        }
      }
      $cant = count($required_words); // cuento las palabras requeridas para saber cuantas mas necesito
      $fill_words = array_slice($words,0,Game::IMAGE_COUNT-$cant); // extraigo las palabras que necesito de las que quedaron sin usar
      $results = array_merge($required_words, $fill_words); // junto los arrays en el resultado final
      return $results;
    }

}
