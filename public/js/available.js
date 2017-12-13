// script para la vista de partidas publicas disponibles

window.addEventListener('load', startAvailableTimer);
var mode0;
var mode1;

// arranca el timer que actualiza el estado de partidas publicas
function startAvailableTimer() {
  mode0 = document.getElementById('mode0-available'); // elemento que contiene el numero de partidas publicas en modo tiempo
  mode1 = document.getElementById('mode1-available'); // elemento que contiene el numero de partidas publicas en modo puntos
  getAvailable();
  setInterval(getAvailable, 1000); // timer que actualiza haciendo llamado ajax
}

function getAvailable() {
  ajaxCall('/api/games/availablepublic', updateAvailable);
}

// funcion que actualiza en pantalla las partidas disponibles recibiendo el objeto que devuelve el llamado a ajax
function updateAvailable(available) {
  if (available.tiempo > 0) {
    mode0.innerHTML = "PARTIDAS DISPONIBLES: " + available.tiempo;
    enableAnchor(mode0.parentElement);
  } else {
    mode0.innerHTML = "NO HAY PARTIDAS DISPONIBLES";
    disableAnchor(mode0.parentElement);
  }
  if (available.puntaje > 0) {
    mode1.innerHTML = "PARTIDAS DISPONIBLES: " + available.puntaje;
    enableAnchor(mode1.parentElement);
  } else {
    mode1.innerHTML = "NO HAY PARTIDAS DISPONIBLES";
    disableAnchor(mode1.parentElement);
  }
}

// habilita el boton para unirse a la partida
function enableAnchor(anchor) {
  anchor.style.pointerEvents = "all";
  anchor.style.cursor = "pointer";
  anchor.className = "button game-mode-button back-color-1";
}

// deshabilita el boton (se llama si no hay partidas disponibles)
function disableAnchor(anchor) {
  anchor.style.pointerEvents = "none";
  anchor.style.cursor = "default";
  anchor.className = "button game-mode-button back-color-3";
}
