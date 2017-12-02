window.addEventListener('load', mainFriends)

var searchInput;
var autosearchTimer;
var tab;
var userListContainer;
var friendListContainer;

function switchPanel(index) {
  var panels = document.getElementsByClassName('switch-panel');
  for (var i=0; i< panels.length; i++) {
    panels[i].style.opacity="0";
    panels[i].style.pointerEvents="none";
  }
  panels[index].style.opacity="1";
  panels[index].style.pointerEvents="all";
  tab = index;
  setCookie('active-user-tab', index);
  if (tab==0) {
    searchInput.value = getCookie('friend-search-text', '');
  }
  if (tab==1) {
    searchInput.value = getCookie('user-search-text', '');
  }
  searchInput.focus();

}

function mainFriends() {
  friendListContainer = document.getElementById('friend-list-container');
  userListContainer = document.getElementById('user-list-container');
  searchInput = document.getElementById('search');
  searchInput.oninput = startAutosearch;
  tab = getCookie('active-user-tab');
  if (tab == "") tab = 0;
  switchPanel(tab);
  document.querySelectorAll("input[type='radio']")[tab].checked=true;
  if (tab == 1) searchInput.value = getCookie('user-search-text');
  if (searchInput.value) searchUsers();
  document.querySelectorAll('.header-item').forEach(function(e) { e.addEventListener('click', clearCookies)});
}

function startAutosearch() {
  clearTimeout(autosearchTimer);
  autosearchTimer = setTimeout(searchUsers, 1000);
}

function searchUsers() {
  if (tab == 0) {
    setCookie('friend-search-text', searchInput.value);
    var userList = friendListContainer.children;
    for (var i=0; i<userList.length; i++) {
      var user = userList.item(i);
      var username = user.querySelector('.user-item-username').innerHTML.toUpperCase();
      var fullname = user.querySelector('.user-item-fullname').innerHTML.toUpperCase();
      var searchString = searchInput.value.toUpperCase();
      if (username.includes(searchString) || fullname.includes(searchString)) {
          user.style.display = "block";
        } else {
          user.style.display = "none";
        }
    }
  }
  if (tab == 1) {
    setCookie('user-search-text', searchInput.value);
    if (searchInput.value.length > 3) {
      var ajax = new XMLHttpRequest();
      ajax.onload = updateUserResults;
      ajax.open("GET", "/searchusers/" + searchInput.value);
      ajax.send();
    } else userListContainer.innerHTML = "";
  }
}

function updateUserResults() {
  userListContainer.innerHTML = this.responseText;
}

function clearCookies() {
  setCookie('user-search-text', '');
  setCookie('friend-search-text', '');
  setCookie('active-user-tab', 0);
}
