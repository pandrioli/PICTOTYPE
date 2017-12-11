// script para la vista de amigos / busqueda de usuarios

window.addEventListener('load', mainFriends)

var searchInput; // input que contiene el string a buscar
var autosearchTimer; // timer del auto search
var tab; // variable que contiene que pestaña esta activa 0 o 1
var userListContainer; // container de la busqueda de usuarios
var friendListContainer; // container de la lista de amigos

// funcion para activar el panel segun la pestaña (index)
function switchPanel(index) {
  var panels = document.getElementsByClassName('switch-panel'); // obtiene los paneles
  for (var i=0; i< panels.length; i++) { // setea los paneles a no visibles
    panels[i].style.opacity="0";
    panels[i].style.pointerEvents="none";
  }
  // activa el panel que tiene que estar visible
  panels[index].style.opacity="1";
  panels[index].style.pointerEvents="all";
  tab = index; // setea la pestaña activa
  setCookie('active-user-tab', index); // guarda una cookie con la pestaña activa
  // recupera la cookie con el string a buscar
  if (tab==0) {
    searchInput.value = getCookie('friend-search-text', '');
  }
  if (tab==1) {
    searchInput.value = getCookie('user-search-text', '');
  }
  searchInput.focus(); // le da foco al input de busqueda

}

// funcion principal cuando se carga la pagina
function mainFriends() {
  // instanciacion de los elementos html
  friendListContainer = document.getElementById('friend-list-container');
  userListContainer = document.getElementById('user-list-container');
  searchInput = document.getElementById('search');
  searchInput.oninput = startAutosearch; // cuando se escribe algo en la busqueda, arrancar el timer de autobusqueda
  tab = getCookie('active-user-tab'); // setea la pestaña activa guardada en la cookie
  if (tab == "") tab = 0; // si no hay cookie, la pestaña es 0 - amigos
  if (tab == 0)   setCookie('user-search-text', ''); // si la pestaña es amigos, borra la cookie del string de busqueda de usuarios
  switchPanel(tab); // activa el panel seguna la pestaña activa
  document.querySelectorAll("input[type='radio']")[tab].checked=true; // pone checked al radio de la pestaña activa
  if (tab == 1) searchInput.value = getCookie('user-search-text'); // si la pestaña es 1, busqueda de usuarios, recupera la string de busqueda
  if (searchInput.value) searchUsers(); // si hay algo en el string de busqueda, buscar usuarios
  // si se toca un boton del header, borrar las cookies
  document.querySelectorAll('.header-item').forEach(function(e) { e.addEventListener('click', clearCookies)});
}

// arranca el autosearch despues de un segundo, borra el timer anterior, de esta manera solo busca si se deja de tipear por un segundo
function startAutosearch() {
  clearTimeout(autosearchTimer);
  autosearchTimer = setTimeout(searchUsers, 1000);
}

// buscar usuarios
function searchUsers() {
  if (tab == 0) { // filtro de busqueda de amigos
    setCookie('friend-search-text', searchInput.value); // setea la cookie del filtro de busqueda de amigos
    var userList = friendListContainer.querySelectorAll(".user-item"); // selecciona todos los items html de amigos
    for (var i=0; i<userList.length; i++) { // loopea entre todos los items
      var user = userList.item(i);
      var username = user.querySelector('.user-item-username').innerHTML.toUpperCase();
      var fullname = user.querySelector('.user-item-fullname').innerHTML.toUpperCase();
      var searchString = searchInput.value.toUpperCase();
      // aplica el filtro segun el search string, busca por username o fullname
      if (username.includes(searchString) || fullname.includes(searchString)) {
          user.style.display = "block";
        } else {
          user.style.display = "none";
        }
    }
  }
  if (tab == 1) { // busqueda de usuarios
    setCookie('user-search-text', searchInput.value); // setea la cookie con el string de busqueda
    if (searchInput.value.length > 3) { // si el string de busqueda es mayor a 3
      // carga con ajax la busqueda de usuarios en la ruta "/user/search"
      var ajax = new XMLHttpRequest();
      ajax.onload = updateUserResults;
      ajax.open("GET", "/user/search/" + searchInput.value);
      ajax.send();
    } else userListContainer.innerHTML = ""; // sino borra la busqueda
  }
}

// muestra los resultados de la busqueda
function updateUserResults() {
  userListContainer.innerHTML = this.responseText;
}

// borra las cookies
function clearCookies() {
  setCookie('user-search-text', '');
  setCookie('friend-search-text', '');
  setCookie('active-user-tab', 0);
}
