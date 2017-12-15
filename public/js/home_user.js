// script del home del usuario logueado

window.addEventListener('load', main);

var userID; // id del usuario
var container_notif; // contenedor de notificaciones
var timestampNotif; // timestamp referencia para pedir partidas actualizadas y notificaciones nuevas
var timestampGames
var timeoutGames; // timeouts de llamadas ajax
var timeoutNotif;

function main() {
  // borra las cookies usadas por la vista de amigos y usuarios
  setCookie('user-search-text', '');
  setCookie('friend-search-text', '');
  setCookie('active-user-tab', 0);
  container_notif = document.getElementById('notifications-container'); // instancia el contenedor de notificaciones
  userID = parseInt(document.getElementById('user-id').innerHTML); // obtiene la id del usuario
  timestampNotif = document.getElementById('timestamp').innerHTML; // obtiene el timestamp al momento de cargar la pagina
  timestampGames = timestampNotif;
  // cuando se toca algun boton del header, pasar la tab a 0, panel 0 (partidas en curso)
  document.querySelectorAll('.header-item, .button').forEach(function(e) { e.addEventListener('click', function() {
    switchPanel(0);
    clearTimeout(timeoutGames);
    clearTimeout(timeoutNotif);
  })});
  var tab = getCookie('active-home-tab'); // obtiene la pestaña actual guardada en cookie
  if (tab=="") tab = 0; // si no hay cookie, la pestaña es 0
  document.querySelectorAll("input[type='radio']")[tab].checked=true; // setea el radio button asociados a la pestañas activa como checked
  switchPanel(tab); // activa el panel segun la pestaña actual
  ajaxGames();
  ajaxNotif();
  updateNotifAlert(); // actualiza el indicador de notificaciones
}

// activar panel de la pestaña solicitada (index)
function switchPanel(index) {
  setCookie('active-home-tab', index); // guarda una cookie con la pestaña activa
  var panels = document.getElementsByClassName('switch-panel'); // obtiene las instancias de los paneles
  for (var i=0; i< panels.length; i++) { // setea todos los paneles como invisibles
    panels[i].style.opacity="0";
    panels[i].style.pointerEvents="none";
  }
  panels[index].style.opacity="1"; // activa el panel correspondiente
  panels[index].style.pointerEvents="all";
  if (index == 2) setCookie('reading', 'true'); // si se esta en notificaciones, guarda una cookie que indica que se estan leyendo las notificaciones
  else if (getCookie('reading')) notifAllRead(); // si se estaban leyendo las notificaciones y se cambia de tab, setear todas las notificaciones como leidas
}

// marca una notificacion como leida
function notifRead(notif) {
  notif.firstElementChild.innerHTML = "1";
  updateNotifAlert();
  ajaxCall('/api/notifications/setread/' + notif.children.item(1).innerHTML, function() {});
}

// marca todas las notificaciones como leidas y vuelve a obtener la lista de notificaciones
function notifAllRead() {
  setCookie('reading', '');
  ajaxCall('/api/notifications/setallreadandget', function(notifications) {
    container_notif.innerHTML = notifications.html;
    updateNotifAlert();
  });
}

// actualiza el estado del indicador de notificaciones (numero de notificaciones nuevas no leidas)
function updateNotifAlert() {
  var notif_number = 0;
  var notif_alert = document.getElementById('notif-alert');
  var notif = container_notif.children; // obtiene las notificaciones en el container
  for (var i=0; i<notif.length; i++) {
    var read = notif.item(i).firstElementChild;
    if (read && read.innerHTML == "0") { // si hay notificaciones no leidas
      notif_number++; //incrementar el numero de notificaciones no leidas
    }
  }
  if (notif_number>0) { // si hay notificaciones no leidas
    notif_alert.style.display = 'inline-block'; // mostrar el indicador de notificaciones
    notif_alert.innerHTML = notif_number; // mostrar el numero de notificaciones
  } else { // sino, no mostrar nada
    notif_alert.style.display = 'none';
    notif_alert.innerHTML = '';
  }
}

function ajaxGames() {
  ajaxCall('/api/games/updated/'+timestampGames, updateGames);
}

function ajaxNotif() {
  ajaxCall('/api/notifications/new/'+timestampNotif, updateNotif);
}


// actualiza notificaciones recibidas por ajax
function updateNotif(notif) {
  if (notif.html) { // si hay notificaciones
    timestampNotif = notif.timestamp; // setear el timestamp al valor de la ultima notificacion
    container_notif.innerHTML = notif.html + container_notif.innerHTML; // agregar el html de la notificacion a las que ya existen
    updateNotifAlert(); // actualizar el indicador de notificaciones
  }
  timeoutNotif = setTimeout(ajaxNotif, 2000);
}

// actualizar el estado de los juegos de los updates recibidos por ajax
function updateGames(games) {
  if (games.length>0) { // si hay juegos actualizados
    timestampGames = games[0].updated_at; // setear el timestamp con el valor de la ultima actualizacion
    games.forEach(updateHTML); // para cada update recibido, actualizar el html
  }
  timeoutGames = setTimeout(ajaxGames, 2000);
}

// actualizar el html del item de partida
function updateHTML(game) {
  var gameDIV = document.getElementById('game-'+game.id); // obtiene el div que contiene la partida
  if (!gameDIV) return; // si no existe, chau
  var opponentDIV = gameDIV.querySelector(".game-item-opponent"); // obtiene el div con el nombre de oponente
  var avatarDIV = gameDIV.querySelector(".game-item-avatar"); // obtiene el div con el avatar
  var resultDIV = gameDIV.querySelector(".game-item-result"); // obtiene el div del resultado (carita alegre o triste)
  for (var key in game.players) { // para cada jugador de la partida
    var player = game.players[key]; // instancia al jugador
    if (player.id != userID) { // si el jugador no es el usuario actual (o sea es oponente)
      opponentDIV.innerHTML = player.username; // muestra el username del oponente
      avatarDIV.style.backgroundImage = 'url("'+ player.avatar +'")'; // muestra el avatar del oponente
      avatarDIV.style.backgroundPosition = 'center';
      avatarDIV.style.backgroundRepeat = 'no-repeat';
      avatarDIV.style.backgroundSize = 'cover';
    }
  }
  if (game.state == 4) { // si el juego se cancelo
    opponentDIV.innerHTML = "JUEGO CANCELADO"
  }
  // animacion de la carita de resultado
  var animation = 'animation-name: pulsate_result; animation-duration: .5s; animation-direction: alternate; animation-iteration-count: infinite;'
  if (game.state > 2 && game.winner_id == userID) { // el usuario actual gano la partida
    resultDIV.innerHTML = '<i class="fa fa-smile-o" aria-hidden="true" style="color: green;'+animation+'"></i>';
    resultDIV.style.opacity = 1;
  }
  if (game.state > 2 && game.winner_id != userID && game.winner_id != null) { // el usuario actual perdio la partida
    resultDIV.innerHTML = '<i class="fa fa-frown-o" aria-hidden="true" style="color: red;'+animation+'"></i>';
    resultDIV.style.opacity = .4;
  }
}

// agrega un metodo a date para pasarlo al formato mysql
Date.prototype.toMysqlFormat = function() {
    return this.getUTCFullYear() + "-" + twoDigits(1 + this.getUTCMonth()) + "-" + twoDigits(this.getUTCDate()) + "%20" + twoDigits(this.getUTCHours()) + ":" + twoDigits(this.getUTCMinutes()) + ":" + twoDigits(this.getUTCSeconds());
};
// funcion para agregar un 0 a la izquierda si un numero es menor que diez (para las fechas)
function twoDigits(d) {
    if(0 <= d && d < 10) return "0" + d.toString();
    if(-10 < d && d < 0) return "-0" + (-1*d).toString();
    return d.toString();
}
