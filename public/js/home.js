window.onload = main;
var image = new Array(2);
var timer;
var word_timer;
var current_image;
var current_word;
var current_letter;
var index;
var image_file;

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
    switch_image(0);
  }, 1000);
}

function image_timer() {
  var other_image = current_image == 0 ? 1 : 0;
  TweenLite.to(image[other_image], 2, {opacity: 0});
  TweenLite.to(image[current_image], 2, {opacity: 1});
  clearInterval(word_timer);
  current_word = image_file.substring(0, image_file.length-4).toUpperCase().replace(/_/g,"Ñ");
  current_letter = 0;
  word_timer = setInterval(show_word, 200);
  setTimeout(switch_image, 5000);
}

function show_word() {
  current_letter++;
  document.getElementById('image-word').innerHTML = current_word.substring(0,current_letter);
}

function switch_image(n) {
  current_image = current_image == 0 ? 1 : 0;
  index++;
  if (index==filenames.length) index=0;
  image_file = filenames[index];
  full_path = image_path+"/pictotypes/"+image_file;
  image[current_image].src = full_path;
  image[current_image].onload = image_timer;
}
