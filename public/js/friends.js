function switchPanel(index) {
  var panels = document.getElementsByClassName('switch-panel');
  for (var i=0; i< panels.length; i++) {
    panels[i].style.opacity="0";
    panels[i].style.pointerEvents="none";
  }
  panels[index].style.opacity="1";
  panels[index].style.pointerEvents="all";
}
