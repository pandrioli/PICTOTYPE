window.onload = mainRegister;
var input_username;
var input_email;
var input_pass;
var input_confirm;
var error_username;
var error_email;
var error_pass;
var error_confirm;
var register_button;
var error = false;

function mainRegister() {
   input_username = document.getElementById('username');
   input_email = document.getElementById('email');
   input_pass = document.getElementById('password');
   input_confirm = document.getElementById('password-confirm');
   error_username = document.getElementById('error-username');
   error_email = document.getElementById('error-email');
   error_pass = document.getElementById('error-pass');
   error_confirm = document.getElementById('error-confirm');
   register_button = document.getElementById('register');
   register_button.style.display = "none";
   register_button.onclick = function(e) {
     e.preventDefault();
     check();
     if (!error) document.querySelector('form').submit();
   };
   document.querySelectorAll('input').forEach(function(input) {
     input.oninput = check;
   });
}

function check() {
  error = false;
  error_username.innerHTML = "";
  error_email.innerHTML = "";
  error_pass.innerHTML = "";
  error_confirm.innerHTML = "";
  if (input_username.value.length < 4) {
    error_username.innerHTML = "MINIMO 4 CARACTERES";
    error = true;
  }
  if (input_username.value.length > 15) {
    error_username.innerHTML = "MAXIMO 15 CARACTERES";
    error = true;
  }
  if (input_email.value.length > 50) {
    error_username.innerHTML = "MAXIMO 50 CARACTERES";
    error = true;
  }
  if (!validateEmail(input_email.value)) {
    if (input_email.value) error_email.innerHTML = "EMAIL INVALIDO";
    error = true;
  }
  if (input_pass.value.length < 6) {
    if (input_pass.value) error_pass.innerHTML = "MINIMO 6 CARACTERES";
    error = true;
  }
  if (input_pass.value != input_confirm.value) {
    if (input_confirm.value) error_confirm.innerHTML = "NO COINCIDEN";
    error = true;
  }
  register_button.style.display = error?'none':'block';
}

function validateEmail(email) {
    var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    return re.test(email.toLowerCase());
}
