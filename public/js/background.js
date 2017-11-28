window.addEventListener('load', startBackgroundTimer);
var time = 0;
var cycle = 2000;
var radius = 1000;
var pi = 3.1416;

function startBackgroundTimer() {
  setInterval(moveBackground, 1000/30);
}

function moveBackground() {
  time++;
  time = time % cycle;
  var t = time*pi/cycle*2;
  var x = Math.round(Math.sin(t) * radius);
  var y = Math.round(Math.cos(t) * radius);
  document.body.style.backgroundPosition = x + "px " + y + "px";
}
