window.onload = main;
var image = new Array(2);
var timer;
var word_timer;
var current_image;
var current_word;
var current_letter;
var index;

function main() {
  image[0] = document.getElementById('image-0');
  image[1] = document.getElementById('image-1');
  image[0].style.opacity = 0;
  image[1].style.opacity = 0;
  index = 0;
  current_image = 0;
  current_letter = 0;
  current_word = ""
  setTimeout(function() {
    load_image(0);
    TweenLite.to(image[0], 1, {opacity: 1});
    word_timer = setInterval(show_word, 200);
    timer = setInterval(switch_image, 5000);
  }, 1000);
}

function switch_image() {
  if (current_image == 0) {
    TweenLite.to(image[0], 2, {opacity: 0});
    current_image = 1;
    load_image(1);
  } else {
    TweenLite.to(image[1], 2, {opacity: 0});
    current_image = 0;
    load_image(0);
  }
  TweenLite.to(image[current_image], 2, {opacity: 1});
  clearInterval(word_timer);
  current_letter = 0;
  word_timer = setInterval(show_word, 200);
}

function show_word() {
  current_letter++;
  document.getElementById('image-word').innerHTML = current_word.substring(0,current_letter);
}

function load_image(n) {
  index++;
  if (index==filenames.length) index=0;
  var new_file = filenames[index];
  full_path = image_path+"/pictotypes/"+new_file;
  image[n].src = full_path;
  current_word = new_file.substring(0, new_file.length-4).toUpperCase().replace(/_/g,"Ñ");
}
