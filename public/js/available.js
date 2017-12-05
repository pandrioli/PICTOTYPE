window.addEventListener('load', startAvailableTimer);
var mode0;
var mode1;
var ajax;

function startAvailableTimer() {
  mode0 = document.getElementById('mode0-available');
  mode1 = document.getElementById('mode1-available');
  setInterval(ajaxCall('/api/games/availablepublic', updateAvailable), 1000);
}

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

function enableAnchor(anchor) {
  anchor.style.pointerEvents = "all";
  anchor.style.cursor = "pointer";
  anchor.className = "button game-mode-button back-color-1";
}

function disableAnchor(anchor) {
  anchor.style.pointerEvents = "none";
  anchor.style.cursor = "default";
  anchor.className = "button game-mode-button back-color-3";
}
