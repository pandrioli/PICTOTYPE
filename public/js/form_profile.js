window.onload = mainProfile;
var countrySelect;
var currentCountry;
var input_first_name;
var input_last_name;
var error_first_name;
var error_last_name;
var save_button;
var error = false;

function mainProfile() {
  input_first_name = document.getElementById('first_name');
  input_last_name = document.getElementById('last_name');
  error_first_name = document.getElementById('first_name_error');
  error_last_name = document.getElementById('last_name_error');
  save_button = document.getElementById('save');
  countrySelect = document.getElementById('country');
  currentCountry = document.getElementById('current-country').innerHTML;
  ajaxCall('https://restcountries.eu/rest/v2/all?fields=translations', fillCountries);
  input_first_name.oninput = checkLengths;
  input_last_name.oninput = checkLengths;
}

function checkLengths() {
  error = false;
  if (input_first_name.value.length > 15) {
    error = true;
    error_first_name.innerHTML = "maximo 15 caracteres";
  } else {
    error_first_name.innerHTML = "";
  }
  if (input_last_name.value.length > 15) {
    error = true;
    error_last_name.innerHTML = "maximo 15 caracteres";
  } else {
    error_last_name.innerHTML = "";
  }
  save_button.style.display = error?'none':'block';
}

function fillCountries(countries) {
  countrySelect.innerHTML = "<option value=''>NO ESPECIFICADO</option>";
  countries.forEach(function(country) {
    var opt = document.createElement('option');
    opt.value = country.translations.es;
    opt.innerHTML = country.translations.es;
    if (opt.innerHTML == currentCountry) opt.selected = true;
    countrySelect.appendChild(opt);
  })
}
