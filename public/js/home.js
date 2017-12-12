// script para el home de usuario no logueado

window.onload = main;
var image = new Array(2); // array de dos imagenes
var timer; // timer de la animacion de imagenes
var word_timer; // timer de la palabra
var current_image; // imagen actual
var current_word; // palabra actual
var current_letter; // letra actual de la animacion de la palabra
var index; // indice de la imagen que se esta mostrando
var image_file;

function main() {
  image[0] = document.getElementById('image-0'); // obtiene el elemento con la imagen 0
  image[1] = document.getElementById('image-1'); // obtiene el elemento con la imagen 1
  image[0].style.opacity = 0; // las hace invisibles
  image[1].style.opacity = 0;
  index = 0; // incializa el index
  current_image = 0; // inicializa la imagen actual
  current_letter = 0; // inicializa la letra actual
  current_word = "" // inicializa la palabra actual
  setTimeout(function() { // espera un segundo para empezar las animaciones de imagenes/palabras
    switch_image(0);
  }, 1000);
}

function image_timer() { // timer de las imagenes
  var other_image = current_image == 0 ? 1 : 0; // imagen que no es la actual
  TweenLite.to(image[other_image], 2, {opacity: 0}); // animacion de opacidad a 0 para la imagen que no es la actual
  TweenLite.to(image[current_image], 2, {opacity: 1}); // animacion de opacidad a 1 para la imagen actual
  clearInterval(word_timer); // borra el timer de la palabra
  current_word = image_file.substring(0, image_file.length-4).toUpperCase().replace(/_/g,"Ñ"); // obtiene la palabra actual en mayusculas, reemplazando _ por Ñ
  current_letter = 0; // setea la letra actual a 0
  word_timer = setInterval(show_word, 200); // inicializa el timer de la animacion de la palabra
  setTimeout(switch_image, 5000); // espera 5 segundos para cambiar de imagen
}

function show_word() { // muestra progresivamente la palabra de la imagen actual, letra por letra
  current_letter++; // aumenta el contador de la letra actual
  document.getElementById('image-word').innerHTML = current_word.substring(0,current_letter); // actualiza el html con la palabra formada hasta la letra actual
}

// cambia de imagen
function switch_image(n) {
  current_image = current_image == 0 ? 1 : 0; // pasa la imagen actual de 0 a 1 o de 1 a 0
  index++; // incrementa el index de las imagenes
  if (index==filenames.length) index=0; // si se llego a la imagen final, vuelve a la primer imagen
  image_file = filenames[index]; // nombre del archivo de la imagen actual segun index. filenames esta creada en el archivo blade mediante javascript embebido
  full_path = image_path+"/pictotypes/"+image_file; // path completo a la imagen
  image[current_image].src = full_path; // actualiza el source del img
  image[current_image].onload = image_timer; // al cargar la imagen, ir al timer que hace la animacion (fade) y espera 5 segundos
}
