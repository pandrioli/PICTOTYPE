// script para mover el background

window.addEventListener('load', startBackgroundTimer);
var timeBackground = 0;
var cycle = 2000;
var radius = 1000;
var pi = 3.1416;

function startBackgroundTimer() {
  setInterval(moveBackground, 1000/30);
}

function moveBackground() {
  timeBackground++;
  timeBackground = timeBackground % cycle;
  var t = timeBackground*pi/cycle*2;
  var x = Math.round(Math.sin(t) * radius);
  var y = Math.round(Math.cos(t) * radius);
  document.body.style.backgroundPosition = x + "px " + y + "px";
}
