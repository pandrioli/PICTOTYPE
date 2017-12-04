var time;
var timer;
var phrase = "";
var game_id;
var user_id;
var game_mode;
var time_per_letter;
var current_letter;
var win;
var image_count;
var images;
var letter_timer;
var letter_time = 10;
var score;

var tutorial;
var tutorial_container;
var tutorial_message;
function start() {
  game_id = document.getElementById("game-id").innerHTML;
  user_id = document.getElementById("user-id").innerHTML;
  game_mode = parseInt(document.getElementById("game-mode").innerHTML);
  image_count = parseInt(document.getElementById("image-count").innerHTML);
  letter_time = parseInt(document.getElementById("time-per-letter").innerHTML);
  phrase = document.getElementById("phrase-text").innerHTML;

  tutorial_container = document.getElementById('tutorial-container');
  tutorial_message = document.getElementById('tutorial-message');
  document.getElementById('ok-button').onclick = hide_tutorial;

  //boton para terminar la partida - developer mode
  var cheatbutton = document.createElement('button');
  cheatbutton.innerHTML = "SALTEAR PARTIDA - MODO DESARROLLO"
  cheatbutton.onclick = win_game;
  cheatbutton.style.position = "fixed";
  cheatbutton.style.top = "0";
  document.body.appendChild(cheatbutton);
  //phrase = "A";

  if (game_mode == 3) {
    tutorial = true;
    game_mode = 0;
  }

  letter_timer = document.getElementById("letter-timer");
  score = 0;
  win = false;
  if (game_mode==0) {
    time = 0;
    timer = setInterval(refresh_time_1, 100);
  } else {
    set_letter_timer();
  }
  current_letter = 0;
  images = Array.from(document.getElementById('images-container').children);
  document.getElementById("game-container").hidden = false;
  document.getElementById("game-loader").hidden = true;
  refresh_phrase();

  //tutorial = true;

  if (tutorial) {
    show_tutorial(0);
    show_images_help();
  } else show_popup("¡EMPIEZA!", "white", false);
}

function set_letter_timer() {
  time = letter_time+1;
  clearInterval(timer);
  timer = setInterval(refresh_time_2, 1000);
}

function letter_advance() {
  current_letter++;
  if (tutorial && current_letter == 5) show_tutorial(1);
  if (tutorial && current_letter == 11) show_tutorial(2);
  if (get_current_letter() == " ") current_letter++;
  if (current_letter == phrase.length) win_game();
  refresh_phrase();
}

function image_click(image) {
  if (win) return;
  if (game_mode==1) set_letter_timer()
  var word = image.id.substring(5);
  var right_image = check_word(word);
  parent_div = image.parentElement;
  if (right_image) {
    parent_div.style.backgroundColor = "green";
  } else {
    parent_div.style.backgroundColor = "red";
  }
  TweenLite.to(image, .3, {opacity: .3, ease: Power3.easeOut, onComplete: function() {
    TweenLite.to(image, .1, {opacity: tutorial ? .5 : 1, ease: Power3.easeOut, onComplete: shuffle_images()});
  }});
}

function check_word(word) {
  letter = get_current_letter();
  letter_pos = word.search(letter);
  if (game_mode == 0) {
    if (letter_pos>-1) return right_word(); else return wrong_word();
  }
  if (game_mode == 1) {
    var score_obtained = 10;
    if (letter_pos == 0) score_obtained = 20;
    if (letter == word.substring(word.length-1)) score_obtained = 35;
    if (letter_pos == -1) score_obtained = -5;
    show_popup((score_obtained>0?"+":"")+score_obtained+" pts", score_obtained>0?"lightgreen":"pink", false);
    score+=score_obtained;
    letter_advance();
    return letter_pos>-1;
  }
}

function right_word() {
  letter_advance();
  if (current_letter < phrase.length) {
    show_popup("¡BIEN!", "white", false);
  }
  return true;
}

function wrong_word() {
  current_letter = 0;
  refresh_phrase();
  show_popup("¡OUCH!", "white", false);
  return false;
}

function show_popup(msg, color, stay) {
  letter_timer.style.display = "none";
  var popup = document.getElementById("game-popup");
  popup.innerHTML = msg;
  popup.style.color = color;
  TweenLite.to(popup, .5, {opacity: 1, scale: 2, delay: .3, ease: Power3.easeOut, onComplete: function() {
    if (!stay) TweenLite.to(popup, .5, {opacity:0, scale: 1, ease: Power3.easeOut, onComplete: function() {
      letter_timer.style.display = "block";
    }});
  }});
}

function get_current_letter() {
  return phrase.substring(current_letter, current_letter+1).toLowerCase().replace(/ñ/g, "_");
}

function shuffle_images() {
  container = document.getElementById("images-container");
  firsts = images.slice(0, image_count-4);
  last4 = images.slice(image_count-4, image_count);
  shuffle(firsts);
  shuffle(last4);
  images = firsts.concat(last4);
  container.innerHTML = "";
  images.forEach(function(image) {
    container.appendChild(image);
  });
  if (tutorial) show_images_help();
}

function shuffle(a) {
  var j, x, i;
  for (i = a.length - 1; i > 0; i--) {
      j = Math.floor(Math.random() * (i + 1));
      x = a[i];
      a[i] = a[j];
      a[j] = x;
  }
}


function refresh_phrase() {
  var done = phrase.substring(0,current_letter).replace(/ /g, "&nbsp");
  var remaining = phrase.substring(current_letter+1).replace(" ", "&nbsp");
  if (!win) document.getElementById("current-letter").innerHTML = get_current_letter().toUpperCase().replace("_", "Ñ");
  else document.getElementById("current-letter").innerHTML = "";
  document.getElementById("done-text").innerHTML = done;
  document.getElementById("remaining-text").innerHTML = remaining;
  if (game_mode == 1) document.getElementById("timer").innerHTML = score + " pts";
}

function refresh_time_1() {
  time++;
  zero_decimals = "";
  zero_seconds = "";
  total_seconds = time/10;
  floor_seconds = Math.floor(total_seconds);
  decimals = Math.floor(60 * (total_seconds - floor_seconds));
  seconds = floor_seconds % 60;
  minutes = Math.floor(floor_seconds/60);
  if (decimals<10) zero_decimals = "0";
  if (seconds<10) zero_seconds = "0";
  document.getElementById("timer").innerHTML = minutes + ":" + zero_seconds + seconds + "." + zero_decimals + decimals;
  //document.getElementById("timer").innerHTML = floor_seconds;
}

function refresh_time_2() {
  time--;
  var scale = 4;
  var msg = time;
  if (time==0) {
    time=letter_time+1;
    msg = "¡TIEMPO!";
    scale = 2;
    letter_advance();
    if (current_letter==phrase.length) return;
  }
  letter_timer.innerHTML = msg;
  //popup.style.color = color;
  TweenLite.to(letter_timer, .5, {opacity: 1, scale: scale, delay: 0, ease: Power3.easeOut, onComplete: function() {
    TweenLite.to(letter_timer, .5, {opacity:0, scale: 1, ease: Power3.easeOut});
  }});

}

function win_game() {
  win = true;
  clearInterval(timer);
  show_popup("¡LISTO!", "white", true);
  var popup = document.getElementById("game-popup");
  popup.onclick = function() {
    location.reload();
  }
  popup.style.pointerEvents = "auto";
  popup.style.cursor = "pointer";
  setTimeout(finish_game, 2000);
}

function finish_game() {
    var method = "post";
    var path = "/game/finish";
    var params = {game_id: game_id, user_id: user_id, time: game_mode==0 ? time : 0, points: game_mode==1 ? score : 0};
    var form = document.createElement("form");
    form.setAttribute("method", method);
    form.setAttribute("action", path);
    form.appendChild(document.getElementsByName('_token')[0]);
    for(var key in params) {
      var hiddenField = document.createElement("input");
      hiddenField.setAttribute("type", "hidden");
      hiddenField.setAttribute("name", key);
      hiddenField.setAttribute("value", params[key]);
      form.appendChild(hiddenField);
    }

    document.body.appendChild(form);
    form.submit();
}

function cancel_game() {
  location.reload();
}


function show_images_help() {
  letter = get_current_letter();
  for (var i in images) {
    images[i].children.item(0).style.zIndex = "-1";
    images[i].children.item(0).style.opacity = ".5";
    images[i].children.item(1).style.opacity = "0";
    images[i].style.backgroundColor = "transparent";
    images[i].children.item(1).style.pointerEvents = "none";
    word = images[i].children.item(1).innerHTML;
    if (word.search(letter) > -1) {
      images[i].children.item(1).style.color = "black";
      images[i].children.item(1).style.opacity = "1";
      images[i].children.item(1).style.zIndex = "0";
      images[i].style.backgroundColor = "rgba(255,255,255,.8)";
    }
  }
}

function show_tutorial(i) {
  var messages = [
    'EL OBJETIVO DEL JUEGO ES ESCRIBIR LA FRASE INDICADA EN LA PARTE SUPERIOR, LETRA POR LETRA, UTILIZANDO LAS IMAGENES QUE REPRESENTAN UNA PALABRA QUE CONTENGA LA LETRA SOLICITADA (LA QUE TITILA) EN ALGUN LUGAR DE LA MISMA. POR EJEMPLO, UNA IMAGEN DE UN OSO, SIRVE PARA ESCRIBIR TANTO LA "O" COMO LA "S". PARA AYUDARTE, LAS OPCIONES CORRECTAS VAN A ESTAR RESALTADAS Y LAS PALABRAS VAN A ESTAR VISIBLES.',
    'EN EL MODO POR TIEMPO, SIMPLEMENTE TENES QUE LOGRAR TERMINAR LA FRASE EN EL MENOR TIEMPO POSIBLE, NO IMPORTA EN QUE LUGAR DE LA PALABRA ESTE LA LETRA SOLICITADA. EN EL MODO POR PUNTOS, EN CAMBIO, TIENES UN TIEMPO LIMITADO PARA CADA LETRA Y EL PUNTAJE POR LETRA ES DE 10 PUNTOS SI LA LETRA SOLICITADA ESTA EN EL MEDIO DE LA PALABRA, 20 PUNTOS SI EMPIEZA CON LA LETRA, Y 35 PUNTOS SI TERMINA CON LA LETRA. SI ELIGES UNA IMAGEN INCORRECTA, SE TE RESTAN 5 PUNTOS.',
    'PUEDES CREAR PARTIDAS PUBLICAS PARA QUE CUALQUIER USUARIO PUEDA UNIRSE A ELLAS, O BIEN UNIRTE A UNA PARTIDA YA CREADA. PARA LAS PARTIDAS PRIVADAS, PUEDES BUSCAR USUARIOS Y ENVIARLES UNA INVITACION, O BIEN AGREGAR AUTOMATICAMENTE A QUIENES TE HAYAN ACEPTADO COMO AMIGO.',
    'ESTE ES EL FIN DEL TUTORIAL ¡ESPERAMOS QUE TE DIVIERTAS!'
  ];
  tutorial_message.innerHTML = messages[i];
  tutorial_container.style.pointerEvents = "all";
  TweenLite.to(tutorial_container, 1, {opacity: 1});
  if (i == 1) game_mode = 1;
  if (i == 2) document.getElementById('ok-button').onclick = function() {show_tutorial(3);};
  if (i == 3) document.getElementById('ok-button').onclick = function() {window.location = "/";};
}

function hide_tutorial() {
  tutorial_container.style.pointerEvents = "none";
  TweenLite.to(tutorial_container, 1, {opacity: 0});
}
