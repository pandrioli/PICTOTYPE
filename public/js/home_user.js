window.addEventListener('load', main);

var ajax;
var timestamp;
var userID;

function main() {
  userID = parseInt(document.getElementById('user-id').innerHTML);
  timestamp = document.getElementById('timestamp').innerHTML;
  document.querySelectorAll('.header-item').forEach(function(e) { e.addEventListener('click', function() {switchPanel(0);})});
  var tab = getCookie('active_tab');
  if (tab=="") tab = 0;
  document.querySelectorAll("input[type='radio']")[tab].checked=true;
  switchPanel(tab);
  setInterval(getUpdatedGames, 1000);
  ajax = new XMLHttpRequest();
  ajax.onload = updateGames;
}

function switchPanel(index) {
  setCookie('active_tab', index)
  var panels = document.getElementsByClassName('switch-panel');
  for (var i=0; i< panels.length; i++) {
    panels[i].style.opacity="0";
    panels[i].style.pointerEvents="none";
  }
  panels[index].style.opacity="1";
  panels[index].style.pointerEvents="all";
}

function getUpdatedGames() {
  var url = "/api/updatedusergames/"+timestamp;
  ajax.open("GET",url);
  ajax.send();
}

function updateGames() {
  var games = JSON.parse(this.responseText);
  if (games.length>0) {
    console.log(this.responseText);
    timestamp = games[0].updated_at;
  }
  games.forEach(updateHTML);
}

function updateHTML(game) {
  var gameDIV = document.getElementById('game-'+game.id);
  var opponentDIV = gameDIV.querySelector(".game-item-opponent");
  var avatarDIV = gameDIV.querySelector(".game-item-avatar");
  var resultDIV = gameDIV.querySelector(".game-item-result");
  for (var key in game.players) {
    var player = game.players[key];
    if (player.id != userID) {
      opponentDIV.innerHTML = player.username;
      avatarDIV.style.backgroundImage = 'url("/'+ player.avatar +'")';
      console.log(player.avatar);
      avatarDIV.style.backgroundPosition = 'center';
      avatarDIV.style.backgroundRepeat = 'no-repeat';
      avatarDIV.style.backgroundSize = 'cover';
    }
  }
  if (game.state == 4) {
    opponentDIV.innerHTML = "JUEGO CANCELADO"
  }
  var animation = 'animation-name: pulsate_result; animation-duration: .5s; animation-direction: alternate; animation-iteration-count: infinite;'
  if (game.state > 2 && game.winner_id == userID) {
    resultDIV.innerHTML = '<i class="fa fa-smile-o" aria-hidden="true" style="color: green;'+animation+'"></i>';
    resultDIV.style.opacity = 1;
  }
  if (game.state > 2 && game.winner_id != userID && game.winner_id != null) {
    resultDIV.innerHTML = '<i class="fa fa-frown-o" aria-hidden="true" style="color: red;'+animation+'"></i>';
    resultDIV.style.opacity = .4;
  }
}


Date.prototype.toMysqlFormat = function() {
    return this.getUTCFullYear() + "-" + twoDigits(1 + this.getUTCMonth()) + "-" + twoDigits(this.getUTCDate()) + "%20" + twoDigits(this.getUTCHours()) + ":" + twoDigits(this.getUTCMinutes()) + ":" + twoDigits(this.getUTCSeconds());
};

function twoDigits(d) {
    if(0 <= d && d < 10) return "0" + d.toString();
    if(-10 < d && d < 0) return "-0" + (-1*d).toString();
    return d.toString();
}
