// script de la pantalla del juego

var time; // tiempo de la partida o tiempo restante para la letra, segun sea el modo.
var timer; // timer de la partida modo 0
var phrase = ""; // frase de la partida
var game_id; // id de la partida
var user_id; // id del usuario que esta jugando
var game_mode; // modo de la partida 0: por tiempo 1: por puntos
var current_letter; // letra actual
var win; // true si ya se termino la partida
var image_count; // cantidad de imagenes
var images; // imagenes
var letter_timer; // mensaje del tiempo por letra;
var letter_time; // tiempo por letra en modo 1
var points; // puntos obtenidos
var tuto_counter; // contador para el tutorial

var tutorial; // true si es tutorial
var tutorial_container; // contenedor del mensaje del tutorial
var tutorial_message; // mensaje del tutorial
function start() {
  tuto_counter = 0; // inicializa el contador del tutorial
  game_id = document.getElementById("game-id").innerHTML; // obtiene la id del juego
  user_id = document.getElementById("user-id").innerHTML; // obtiene el id del jugador
  game_mode = parseInt(document.getElementById("game-mode").innerHTML); // obtiene el modo de juego
  image_count = parseInt(document.getElementById("image-count").innerHTML); // obtiene la cantidad de imagenes
  letter_time = parseInt(document.getElementById("time-per-letter").innerHTML); // obtiene el tiempo por letra
  phrase = document.getElementById("phrase-text").innerHTML; // obtiene la frase de la partida

  tutorial_container = document.getElementById('tutorial-container'); // instancia el contenedor del mensaje tutorial
  tutorial_message = document.getElementById('tutorial-message'); // instancia el elemento del mensaje del tutorial
  document.getElementById('ok-button').onclick = hide_tutorial; // ok button del tutorial, al hacer onclick ejecuta la funcion que oculta el mensaje

  //boton para terminar la partida - developer mode
  var cheatbutton = document.createElement('button');
  cheatbutton.innerHTML = "SALTEAR PARTIDA - MODO DESARROLLO"
  cheatbutton.onclick = win_game;
  cheatbutton.style.position = "fixed";
  cheatbutton.style.top = "0";
  document.body.appendChild(cheatbutton);
  //phrase = "A";

  if (game_mode == 3) { // si la partida viene con modo 3, es tutorial, setea el modo en 0 para arrancar y pone tutorial en true
    tutorial = true;
    game_mode = 0;
  }

  letter_timer = document.getElementById("letter-timer"); // el elemento que muestra el tiempo que queda por letra
  points = 0; // incializa los puntos a cero
  win = false; // win en falso indica que la partida esta en juego
  if (game_mode==0) { // si es modo por tiempo
    time = 0; // inicializa el tiempo en 0
    timer = setInterval(refresh_time_1, 100); // setea el timer que contabiliza el tiempo transcurrido
  } else { // si es modo por puntos
    set_letter_timer(); // inicializa el timer por letra
  }
  current_letter = 0; // posicion de la letra actual en 0
  images = Array.from(document.getElementById('images-container').children); // obtiene los elementos que contienen las imagenes
  document.getElementById("game-container").hidden = false; // muestra el contenedor del juego
  document.getElementById("game-loader").hidden = true; // oculta el mensaje "cargando partida"
  refresh_phrase(); // refresca la frase y otras cosas

  //tutorial = true;

  if (tutorial) { // si es tutorial
    document.getElementById('timer').innerHTML = "TUTORIAL"; // el timer dice TUTORIAL
    show_tutorial(0); // muestra el primer mensaje del tutorial
    show_images_help(); // muestra la ayuda en las imagenes
  } else show_popup("¡EMPIEZA!", "white", false); // si no es tutorial, muestra el mensaje "empieza"
}

function set_letter_timer() { // inicializa el timer por letra del modo 1
  time = letter_time+1;
  clearInterval(timer);
  timer = setInterval(refresh_time_2, 1000);
}

function letter_advance() { // avanza una letra
  tuto_counter++; // contador del tutorial
  current_letter++; // contador de la letra actual
  if (tutorial && tuto_counter == 4) show_tutorial(1); // muestra segundo mensaje del tutorial
  if (tutorial && tuto_counter == 6) show_tutorial(2); // muestra tercer mensaje del tutorial
  if (tutorial && tuto_counter == 11) show_tutorial(3); // muestra cuarto mensaje del tutorial
  if (get_current_letter() == " ") current_letter++; // si es un espacio, pasa a la siguiente letra
  if (current_letter == phrase.length) win_game(); // si ya estan todas las letras, ejecutar la funcion que termina la partida
  refresh_phrase(); // refresca la frase y demas
}

function image_click(image) { // al hacer click en una imagen
  if (win) return; // si ya se termino la partida, no hay nada que hacer
  if (game_mode==1) set_letter_timer() // si el modo es 1, resetea el timer por letra
  var word = image.id.substring(5); // obtiene la palabra de la imagen clickeada
  var right_image = check_word(word); // chequea si la palabra es valida, la funcion check_word devuelve true si esta bien
  parent_div = image.parentElement; // el elemento padre de la imagen
  if (right_image) { // si es la imagen correcta
    parent_div.style.backgroundColor = "green"; // mostrar fondo verde
  } else {
    parent_div.style.backgroundColor = "red"; // sino mostrar fondo rojo
  }
  // animacion de cambio de opacidad, cuando termina ejecuta shuffle_images para mezclar las imagenes
  TweenLite.to(image, .3, {opacity: .3, ease: Power3.easeOut, onComplete: function() {
    TweenLite.to(image, .1, {opacity: tutorial ? .3 : 1, ease: Power3.easeOut, onComplete: shuffle_images()});
  }});
}

// funcion que chequea si la imagen/palabra que se clickeo esta bien y los puntos que se obtiene en modo 1
function check_word(word) {
  letter = get_current_letter(); // obtiene la letra actual
  letter_pos = word.search(letter); // busca la letra actual en la palabra clickeada
  if (game_mode == 0) { // modo por tiempo, devuelve el resultado de la funcion right_word o wrong_word segun corresponda
    if (letter_pos>-1) return right_word(); else return wrong_word();
  }
  if (game_mode == 1) { // modo por pountos
    var points_obtained = 10; // por default setea en 10 puntos
    if (letter_pos == 0) points_obtained = 20; // si empieza con la letra, 20 puntos
    if (letter == word.substring(word.length-1)) points_obtained = 35; // si termina con la letra, 35 puntos
    if (letter_pos == -1) points_obtained = -5; // si no esta la letra en la palabra, resta 5 puntos
    // muestra el mensaje de los puntos obtenidos
    show_popup((points_obtained>0?"+":"")+points_obtained+" pts", points_obtained>0?"lightgreen":"pink", false);
    points+=points_obtained; // acumula los puntos obtenidos
    letter_advance(); // avanza a la siguiente letra
    return letter_pos>-1; // devuelve true o false si la imagen era correcta o no
  }
}

function right_word() { // se eligio la palabra correcta (modo 0)
  letter_advance(); // avanza una letra
  if (current_letter < phrase.length) { // si todavia quedan letras
    show_popup("¡BIEN!", "white", false); // mostrar mensaje "BIEN!"
  }
  return true; // devuelve true, palabra correcta
}

function wrong_word() { // se eligio la palabra incorrecta (modo 0)
  current_letter = 0; // vuelve al principio
  refresh_phrase(); // refresca frase y demas
  show_popup("¡OUCH!", "white", false); // muestra mensaje de que le pifiaste
  return false; // devuelve false, palabra incorrecta
}

// funcion para mostrar mensaje (pop-up) - recibe el mensaje, el color y si se tiene que quedar en pantalla o no
function show_popup(msg, color, stay) {
  letter_timer.style.display = "none"; // deja de mostrar el timer por letra para el modo 1
  var popup = document.getElementById("game-popup"); // instancia el elemento html del popup
  popup.innerHTML = msg; // setea el mensaje del popup
  popup.style.color = color; // setea el color del popup
  // animacion del popup
  TweenLite.to(popup, .5, {opacity: 1, scale: 2, delay: .3, ease: Power3.easeOut, onComplete: function() {
    if (!stay) TweenLite.to(popup, .5, {opacity:0, scale: 1, ease: Power3.easeOut, onComplete: function() {
      letter_timer.style.display = "block";
    }});
  }});
}

// obtiene la letra actual
function get_current_letter() {
  return phrase.substring(current_letter, current_letter+1).toLowerCase().replace(/ñ/g, "_");
}

// mezcla las imagenes, mantiene las ultimas 4 al final porque en modo pantalla chica esas imagenes no estan y
// se debe evitar que una imagen que se necesita si o si caiga al final
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

// funcion que mezcla los elementos de un array
function shuffle(a) {
  var j, x, i;
  for (i = a.length - 1; i > 0; i--) {
      j = Math.floor(Math.random() * (i + 1));
      x = a[i];
      a[i] = a[j];
      a[j] = x;
  }
}

// refrescar el display de la frase y puntos
function refresh_phrase() {
  var done = phrase.substring(0,current_letter).replace(/ /g, "&nbsp");
  var remaining = phrase.substring(current_letter+1).replace(" ", "&nbsp");
  if (!win) document.getElementById("current-letter").innerHTML = get_current_letter().toUpperCase().replace("_", "Ñ");
  else document.getElementById("current-letter").innerHTML = "";
  document.getElementById("done-text").innerHTML = done;
  document.getElementById("remaining-text").innerHTML = remaining;
  if (game_mode == 1 && !tutorial) document.getElementById("timer").innerHTML = points + " pts";
}

// refrescar el display del timer en modo 0
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
  if (!tutorial) document.getElementById("timer").innerHTML = minutes + ":" + zero_seconds + seconds + "." + zero_decimals + decimals;
  //document.getElementById("timer").innerHTML = floor_seconds;
}

// refrescar el display del timer en modo 1
function refresh_time_2() {
  time--;
  var scale = 4; // tamaño del popup
  var msg = time; // el mensaje del popup es el tiempo restante
  if (time==0) { // se cumplio el tiempo por letra
    time=letter_time+1; // resetear tiempo, dar un segundo extra para mostrar mensaje
    msg = "¡TIEMPO!"; // el mensaje del popup es que se cumplio el tiempo
    scale = 2; // tamaño del popup mas chico
    letter_advance(); // avanzar a siguiente letra
    if (tutorial) show_images_help(); // si es tutorial, actualizar la ayuda en las imagenes
    if (current_letter==phrase.length) return; // si ya estan todas las letras, volver
  }
  letter_timer.innerHTML = msg; // actualiza el mensaje del popup del timer
  //popup.style.color = color;
  // animacion del popup del timer
  TweenLite.to(letter_timer, .5, {opacity: 1, scale: scale, delay: 0, ease: Power3.easeOut, onComplete: function() {
    TweenLite.to(letter_timer, .5, {opacity:0, scale: 1, ease: Power3.easeOut});
  }});

}

// finalizacion de la partida fase 1
function win_game() {
  win = true; // setea el flag de que se termino la partida
  clearInterval(timer); // borra el timer
  show_popup("¡LISTO!", "white", true); // muestra el mensaje de que se acabo
  setTimeout(finish_game, 2000); // espera dos segundos para finalizar realmente la partida
}

// finalizacion de la partida fase 2
function finish_game() { // se crea un formulario que contiene todos los datos de la partida para enviarlos por post a la ruta correspondiente
    var method = "post";
    var path = "/game/finish";
    var phrase_length = phrase.replace(/ /g, "").length;
    var letter_average = 0;
    if (game_mode == 0) letter_average = time / phrase_length;
    if (game_mode == 1) letter_average = points / phrase_length;
    var params = {game_id: game_id, user_id: user_id,
      time: game_mode==0 ? time : 0, points: game_mode==1 ? points : 0, letter_average: letter_average};
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

// muestra ayuda en las imagenes para el tutorial
function show_images_help() {
  letter = get_current_letter();
  for (var i in images) {
    images[i].children.item(0).style.zIndex = "-1";
    images[i].children.item(0).style.opacity = ".3";
    images[i].children.item(1).style.opacity = "0";
    images[i].style.backgroundColor = "transparent";
    images[i].children.item(1).style.pointerEvents = "none";
    word = images[i].children.item(0).id.substring(5);
    if (word.search(letter) > -1) {
      images[i].children.item(1).style.color = "black";
      images[i].children.item(1).style.opacity = "1";
      images[i].children.item(1).style.zIndex = "0";
      images[i].children.item(0).style.opacity = ".6";
      images[i].style.backgroundColor = "white";
    }
  }
}

// muestra mensajes del tutorial
function show_tutorial(i) {
  var messages = [
    '<h1>¡BIENVENIDO A PICTOTYPE!</h1>EL OBJETIVO DEL JUEGO ES ESCRIBIR LA FRASE INDICADA EN LA PARTE SUPERIOR, LETRA POR LETRA, UTILIZANDO LAS IMAGENES. LA PALABRA QUE REPRESENTA LA IMAGEN DEBE CONTENER LA LETRA SOLICITADA (LA QUE TITILA) EN ALGUN LUGAR DE LA MISMA. POR EJEMPLO, UNA IMAGEN DE UN OSO, SIRVE PARA ESCRIBIR TANTO LA "O" COMO LA "S". PARA AYUDARTE A ENTENDER EL FUNCIONAMIENTO, LAS OPCIONES CORRECTAS VAN A ESTAR RESALTADAS Y LAS PALABRAS VAN A ESTAR VISIBLES.',
    'LA PALABRA ASOCIADA A LA IMAGEN, ES SIEMPRE LA QUE LA REPRESENTA EN FORMA MAS ESPECIFICA Y NO DE MANERA GENERAL. POR EJEMPLO, LA IMAGEN DE UN PERRO, REPRESENTA A "PERRO" Y NO A "ANIMAL" O "MASCOTA". AL PULSAR SOBRE LA IMAGEN, YA SEA QUE ACERTASTE O NO, SE MOSTRARA BREVEMENTE LA PALABRA ASOCIADA.',
    'EN EL MODO POR TIEMPO, SIMPLEMENTE TENES QUE LOGRAR TERMINAR LA FRASE EN EL MENOR TIEMPO POSIBLE, NO IMPORTA EN QUE LUGAR DE LA PALABRA ESTE LA LETRA SOLICITADA ¡SI TE EQUIVOCAS VOLVES AL PRINCIPIO! EN EL MODO POR PUNTOS, EN CAMBIO, TENES UN TIEMPO LIMITADO PARA CADA LETRA Y EL PUNTAJE POR LETRA ES DE 10 PUNTOS SI LA LETRA SOLICITADA ESTA EN EL MEDIO DE LA PALABRA, 20 PUNTOS SI EMPIEZA CON LA LETRA, Y 35 PUNTOS SI TERMINA CON LA LETRA. SI ELIGES UNA IMAGEN INCORRECTA, SE TE RESTAN 5 PUNTOS.',
    'PUEDES CREAR PARTIDAS PUBLICAS PARA QUE CUALQUIER USUARIO PUEDA UNIRSE A ELLAS, O BIEN UNIRTE A UNA PARTIDA YA CREADA. PARA LAS PARTIDAS PRIVADAS, PUEDES BUSCAR USUARIOS Y ENVIARLES UNA INVITACION, O BIEN AGREGAR AUTOMATICAMENTE A QUIENES TE HAYAN ACEPTADO COMO AMIGO.',
    'ESTE ES EL FIN DEL TUTORIAL <h1>¡ESPERAMOS QUE TE DIVIERTAS!</h1>'
  ];
  tutorial_message.innerHTML = messages[i];
  tutorial_container.style.pointerEvents = "all";
  TweenLite.to(tutorial_container, 1, {opacity: 1});
  if (i == 2) game_mode = 1;
  if (i == 3) document.getElementById('ok-button').onclick = function() {show_tutorial(4);}; // anteultimo mensaje, muestra el mensaje final
  if (i == 4) document.getElementById('ok-button').onclick = function() {window.location = "/";}; // ultimo mensaje, vuelve a home
}

// esconde el mensaje del tutorial
function hide_tutorial() {
  tutorial_container.style.pointerEvents = "none";
  TweenLite.to(tutorial_container, 1, {opacity: 0});
}
