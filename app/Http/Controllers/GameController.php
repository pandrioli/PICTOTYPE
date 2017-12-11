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
use App\UsersStat;
use App\CustomClasses\NotificationManager;

class GameController extends Controller
{
    private $notifyman; // Notification Manager, inyectado en el constructor
    private $words; // palabras disponibles (imagenes)
    public function __construct(NotificationManager $notifyman) {
      $this->notifyman = $notifyman; // asigna el Notification Manager al atributo privado del constructor
      $words = []; // inicializa el array de palabras
      $files = glob(public_path()."/img/pictotypes/*.jpg"); // carga los nombres de archivos de imagenes
      foreach ($files as $file) { // extrae las palabras del nombre de los archivos
        $words[] = pathinfo($file)['filename'];
      }
      $this->words = $words; // asigna al atributo $words las palabras obtenidas
    }

    // inicia el juego en modo tutorial
    public function tutorial() {
      $game = $this->generateGame(3, 20, false); // genero el juego con la funcion privada generateGame
      $user_id = 0; // el tutorial no esta asignado a un usuario
      return view('gameplay', compact('game','user_id')); // devuelvo la vista del juego pasandole las variables $game y $user_id
    }

    // vista para unirse o crear partida publica
    public function publicGame() {
      return view('public_game');
    }

    // unirse a partida publica publico ($mode = modo de juego)
    public function joinPublicGame($mode) {
      $public_game = DB::table('games')->join('games_users', 'games.id', '=', 'games_users.game_id')
        ->where([['games_users.user_id', '<>', Auth::user()->id], ['games.state', 1], ['games.mode', $mode]])
        ->select('games.id as id')
        ->first(); // devuelve el id del primer juego publico que cumpla la condicion de no ser creado por el mismo usuario,
        // que sea el modo elegido y que este disponible para jugar
      $game = Game::find($public_game->id); //instancia el juego
      $this->addPlayer($game->id, Auth::user()); // agrega al usuario al juego mediante la funcion privada addPlayer
      if ($game) return redirect()->route('gameplay', $game->id); // si el juego existe, dirige a la vista de juego
      else return redirect()->route('publicgame'); // si por algun motivo no existe, vuelve a la pantalla publicgame
    }

    // aceptar invitacion de partida (id del juego, id del usuario que invita)
    public function acceptGame($game_id, $user_id) {
      $this->addPlayer($game_id, Auth::user()); // agregarse a la partida
      $this->notifyman->notifyGameAccepted($game_id, $user_id); // notificar que se acepto a la partida
      return redirect()->route('gameplay', $game_id); // mostrar la vista para jugar
    }

    // rechazar invitacion de partida (id del juego)
    public function rejectGame($game_id) {
      $game = Game::find($game_id); // instancia el juego
      $game->state = Game::STATE_CANCELLED; // actualiza el estado de la partida
      $game->setOpponentState(Game::PLAYER_DONE); // pone al oponente en estado terminado
      $game->save(); // guarda los cambios
      $this->notifyman->notifyGameRejected($game_id, $game->opponentPlayer()->id); // notifica que se rechazo la partida
      return redirect()->route('home'); // vuelve a home
    }

    // formulario para crear nueva partida
    public function createGameForm($data) {
      $practique = $data == "practique" ? true : false; // si se envio el texto "practique", setear la variable en true
      $user_id = $data == "public" ? 0 : $data; // si se envio el texto 'public', no se asigna un id de usuario
      // si no es public ni practique, es porque se envio el user_id a quien se esta agregando o invitando  a la partida
      return view('create_game', compact('practique', 'user_id')); // muestra la vista de crear partida
    }

    // creacion de una nueva partida (POST)
    public function createGame(Request $request) {
      $owner = Auth::user(); // dueño de la partida
      $mode = $request->input('mode'); // obtiene el modo (0: por tiempo, 1: por puntaje)
      $practique = $request->input('practique'); // obtiene si es una practica
      $user_id = intval($request->input('user_id')); // obtiene el id del oponente si es una partida privada
      $time_per_letter = $request->input('time-per-letter'); // obtiene el tiempo por letra en caso de modo 1
      $play_now = $practique || $request->input('play_now'); // obtiene si se quiere jugar ahora, o despues (si es practica se juega ahora)
      $game = $this->generateGame($mode, $time_per_letter, $practique); // se genera el juego con la funcion privada generateGame
      if (!$practique) $this->addPlayer($game->id, $owner); // si no es una practica, se agrega al usuario a la partida
      if ($user_id) { // si hay oponente (partida privada)
        $user = User::find($user_id); // instancio al usuario
        if ($owner->friendship($user_id) == User::FRIENDSHIP_ACCEPTED) { // si es amigo
          $this->addPlayer($game->id, $user); // se agrega el amigo a la partida
          $this->notifyman->notifyGameCreated($game->id, $user->id); // se le notifica que fue agregado
        } else { // no es amigo, es una invitacion
          $this->notifyman->notifyGameInvitation($game->id, $user->id); // se notifica la invitacion
          $game->state = Game::STATE_INVITATION; // pone la partida en estado de invitacion
          $game->invitation_id = $user->id; // se agrega el id a quien se esta invitando
          $game->save(); // guarda los cambios
        }
      }
      if ($play_now) return redirect()->route('gameplay', $game->id); // si es para jugar ahora, mostrar la vista para jugar
      else return redirect()->route('home'); // si no vuelve al home
    }


    // ver partida
    public function viewGame($id) {
      $game = Game::find($id); // instancia la partida a partir del id
      $user = $game->currentPlayer(); // obtiene el usuario actual
      // si el usuario actual existe (esta agregado a la partida), o es una partida a la cual lo invitaron, mostrar la vista de ver la partida
      if ($user || $game->invitation_id == Auth::user()->id) return view('view_game', compact('game'));
      else return redirect()->route('home'); // sino volver al home
    }

    // jugar partida (id de la partida)
    public function playGame($id) {
      $game = Game::find($id); // instancia la partida con el id
      if (!$game) return redirect()->route('home'); // si no existe ir a home
      $game->load('players'); // carga los jugadores
      $user_id = Auth::check() ? Auth::user()->id : ''; // obtiene el id del usuario actual si esta logueado
      if (!$game->practique && $game->getPlayerState() != Game::PLAYER_READY) { // si no es una practica, y el estado del jugador no es "listo para jugar"
        if ($game->getPlayerState() == Game::PLAYER_PLAYING) { // si el jugador ya estaba jugando esta partida
          $game->state = Game::STATE_CANCELLED; // cancela el juego
          $game->save();
        }
        return redirect()->route('home'); // va a home
      }
      if (!$game->practique) $game->setPlayerState(Game::PLAYER_PLAYING); // si no es una practica, poner al jugador en modo "jugando"
      return view('gameplay', compact('game','user_id')); // devolver la vista del juego, pasando las variables $game y $user_id
    }

    // terminar juego (POST)
    public function finishGame(Request $request) {
      if (!$request->input('user_id')) return redirect()->route('home'); // si no hay usuario asignado a la partida, volver a home (practica por ejemplo)
      $game = Game::find($request->input('game_id')); // instancia el juego con el id recibido
      $game->load('players'); // carga los jugadores
      $game->setPlayerState(Game::PLAYER_DONE); // pone el estado del jugador actual en "ya jugo"
      $game->setPlayerTime($request->input('time')); // setea el tiempo que obtuvo (modo 0)
      $game->setPlayerPoints($request->input('points')); // setea los puntos que obtuvo (modo 1)
      $game->setPlayerAverage($request->input('letter_average')); // setea el promedio por letra (puede ser tiempo o puntos)
      $opponent = $game->opponentPlayer(); // oponente
      // si hay oponente y ya jugo, y el juego no esta cancelado:
      if ($opponent && $game->getOpponentState()==Game::PLAYER_DONE && $game->state != Game::STATE_CANCELLED) {
        $win = 0; // inicializa a $win en 0, que signficaria empate
        if ($game->mode == 0) { // si es modo por tiempo
          if ($game->getPlayerTime() < $game->getOpponentTime()) $win = 1; // el jugador actual hizo menor tiempo que el oponente, setea win en 1
          if ($game->getPlayerTime() > $game->getOpponentTime()) $win = 2; // el oponente hizo menor tiempo que el jugador actual, setea win en 2
        }
        if ($game->mode == 1) { // si es modo por puntos
          if ($game->getPlayerPoints() > $game->getOpponentPoints()) $win = 1; // el jugador actual hizo mas puntos que el oponente, win=1
          if ($game->getPlayerPoints() < $game->getOpponentPoints()) $win = 2; // el oponente hizo mas puntos que el jugador actual, win=2
        }
        if ($win == 1) { // gano el jugador actual
          $game->winner_id = $game->currentPlayer()->id; // setea el id del ganador en el juego
        }
        if ($win == 2) { // gano el oponente
          $game->winner_id = $game->opponentPlayer()->id; // setea el id del ganador en el juego
        }
        if ($win>0) { // si alguien gano
          $game->state = Game::STATE_FINISHED; //el juego se da por terminado
          $this->saveStats($game); // guardar estadisticas (funcion privada)
        }
        else { // empate - volver a jugar - winner_id 0 indica empate
          $game->state = Game::STATE_WAITING_PLAYS; // vuelve el juego al estado de espera de jugadas
          $game->setPlayerState(Game::PLAYER_READY); // pone al jugador actual en estado "listo para jugar"
          $game->setOpponentState(Game::PLAYER_READY); // lo mismo con el oponente
          $game->winner_id = 0; // el winner_id en 0 significa empate
        }
        $game->save(); // guarda los cambios
        $this->notifyman->notifyGameFinished($game); // notifica la finalizacion del juego
      }
      return redirect()->route('viewgame', $game->id); // devuelve la vista de ver partida (resultados)
    }

    // cancelar juego
    public function cancelGame($game_id, $user_id) {
      $game = Game::find($game_id); // instancia el juego por el id
      if ($game->currentPlayer()->id == $user_id) { // si el jugador que cancela pertenece a la partida
        $game->state = Game::STATE_CANCELLED; // setea el estado en cancelado
        $game->setPlayerState(Game::PLAYER_DONE); // setea el estado del jugador en "terminado"
        if ($game->opponentPlayer()) { // si hay oponente
          $game->setOpponentState(Game::PLAYER_DONE); // setea el estado del oponente en "terminado"
          $this->notifyman->notifyGameCancelled($game); // notifica al oponente que se cancelo la partida
        }
        $game->save(); // guarda los cambios
      }
      return redirect()->route('home'); // vuelve a home
    }

    // FUNCIONES PRIVADAS

    // agregar jugador a la partida (id del juego, instancia del usuario)
    private function addPlayer($game_id, $user) {
      $game = Game::find($game_id); // instancia el juego por id
      $game->players()->attach($user); // agrega el usuario a los jugadores de la partida
      if ($game->opponentPlayer()) { // si ya hay un oponente
        $game->state = Game::STATE_WAITING_PLAYS; // setea el estado en "espera de jugadas"
      }
      $game->save(); // guarda los cambio
    }

    // guardar las estadisticas de la partida (recibe instancia de la partida)
    private function saveStats($game) {
      foreach ($game->players as $user) { // para cada jugador de la partida
        if ($user->stats) { // si ya tiene registro en la tabla de stats
          $stats = $user->stats; // setea $stats con los stats actuales del jugador
        } else { // no tiene registro en tabla de stats aun
          $stats = new UsersStat(); // creo una instancia de la clase que de stats de jugador
          $stats->user_id = $user->id; // setea el id de usuario
          // inicializa los stats
          $stats->played = 0;
          $stats->wins = 0;
          $stats->losses = 0;
          $stats->time_per_letter = 0;
          $stats->points_per_letter = 0;
        }
        if ($game->mode == 0) { // si el juego es por modo tiempo, actualiza el promedio de tiempo por letra
          // con una formula que es (valor actual * partidas jugadas + promedio obtenido en la partida) / (partidas jugadas + 1)
          // de esta manera el promedio obtenido en la partida afecta proporcionalmente al promedio actual segun la cantidad de partidas jugadas
          $stats->time_per_letter = ($stats->time_per_letter * $stats->played + $user->pivot->letter_average) / ($stats->played+1);
        }
        if ($game->mode == 1) { // si es en modo puntos, hace lo mismo pero con los puntos
          $stats->points_per_letter = ($stats->points_per_letter * $stats->played + $user->pivot->letter_average) / ($stats->played+1);
        }
        $stats->played++; // suma una partida jugada mas
        if ($game->winner_id == $user->id) $stats->wins++; else $stats->losses++; // suma una ganada o perdida, segun sea el caso
        $stats->wins_ratio = $stats->wins / $stats->played; // setea la proporcion de partidas ganadas
        $stats->save(); // guarda las estadisticas
      }
    }

    // generar nueva partida (recibe modo, tiempo por letra para modo 1 y si es practica)
    private function generateGame($mode, $time = 0, $practique = false) {
      $game = new Game(); // nueva instancia de juego
      $phrase = Phrase::inRandomOrder()->first(); // elige una frase al azar
      //$phrase = Phrase::find(3); 
      $game->practique = $practique; // setea si es modo practica
      $game->phrase_id = $phrase->id; // setea el id de la frase
      $game->mode = $mode; // setea el modo
      $game->time_per_letter = $time; // setea el tiempo por letra (para modo 1)
      $game->state = Game::STATE_WAITING_OPPONENT; // setea el estado en esperando oponente
      $game->save(); // guarda la partida
      $words = $this->getGameWords($phrase->phrase); // obtiene una lista de palabras (imagenes) para el juego, segun la frase
      foreach ($words as $word) { // guarda las palabras/imagenes de la partida
        $game_word = new Word();
        $game_word->word = $word;
        $game_word->game_id = $game->id;
        $game_word->save();
      }
      return $game; // devuelve la partida creada
    }

    // obtener una lista de palabras para una partida con determinada frase.
    // el algoritmo asegura que en las palabras esten las letras necesarias para formar la frase.
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
