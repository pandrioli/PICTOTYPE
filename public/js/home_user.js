window.addEventListener('load', main);

var timestamp;
var userID;
var container_notif;

function main() {
  setCookie('user-search-text', '');
  setCookie('friend-search-text', '');
  setCookie('active-user-tab', 0);
  container_notif = document.getElementById('notifications-container');
  userID = parseInt(document.getElementById('user-id').innerHTML);
  timestamp = document.getElementById('timestamp').innerHTML;
  document.querySelectorAll('.header-item, .button').forEach(function(e) { e.addEventListener('click', function() {switchPanel(0);})});
  var tab = getCookie('active-home-tab');
  if (tab=="") tab = 0;
  document.querySelectorAll("input[type='radio']")[tab].checked=true;
  switchPanel(tab);
  setInterval(getUpdates, 2000);
  updateNotifAlert();
}

function switchPanel(index) {
  setCookie('active-home-tab', index)
  var panels = document.getElementsByClassName('switch-panel');
  for (var i=0; i< panels.length; i++) {
    panels[i].style.opacity="0";
    panels[i].style.pointerEvents="none";
  }
  panels[index].style.opacity="1";
  panels[index].style.pointerEvents="all";
  if (index == 2) setCookie('reading', 'true');
  else if (getCookie('reading')) notifAllRead();
}

function notifRead(notif) {
  notif.firstElementChild.innerHTML = "1";
  updateNotifAlert();
  ajaxCall('/api/notifications/setread/' + notif.children.item(1).innerHTML, function() {});
}

function notifAllRead() {
  setCookie('reading', '');
  ajaxCall('/api/notifications/setallreadandget', function(notifications) {
    container_notif.innerHTML = notifications.html;
    updateNotifAlert();
  });
}


function updateNotifAlert() {
  var notif_number = 0;
  var notif_alert = document.getElementById('notif-alert');
  var notif = container_notif.children;
  for (var i=0; i<notif.length; i++) {
    var read = notif.item(i).firstElementChild;
    if (read && read.innerHTML == "0") {
      notif_number++;
    }
  }
  if (notif_number>0) {
    notif_alert.style.display = 'inline-block';
    notif_alert.innerHTML = notif_number;
  } else {
    notif_alert.style.display = 'none';
    notif_alert.innerHTML = '';
  }
}


function getUpdates() {
  ajaxCall('/api/games/updated/'+timestamp, updateGames);
  ajaxCall('/api/notifications/new/'+timestamp, updateNotif);
}

function updateNotif(notif) {
  if (notif.html) {
    timestamp = notif.timestamp;
    container_notif.innerHTML = notif.html + container_notif.innerHTML;
    updateNotifAlert();
  }
}

function updateGames(games) {
  if (games.length>0) {
    timestamp = games[0].updated_at;
    games.forEach(updateHTML);
  }
}

function updateHTML(game) {
  var gameDIV = document.getElementById('game-'+game.id);
  if (!gameDIV) return;
  var opponentDIV = gameDIV.querySelector(".game-item-opponent");
  var avatarDIV = gameDIV.querySelector(".game-item-avatar");
  var resultDIV = gameDIV.querySelector(".game-item-result");
  for (var key in game.players) {
    var player = game.players[key];
    if (player.id != userID) {
      opponentDIV.innerHTML = player.username;
      avatarDIV.style.backgroundImage = 'url("'+ player.avatar +'")';
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
