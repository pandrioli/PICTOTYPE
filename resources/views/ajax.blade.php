<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>ejercitacion AJAX</title>
  </head>
  <body onload='main()'>
    <script type="text/javascript">
      function main() {
        ajaxCall('http://pilote.techo.org/?do=api.getPaises', addCountries);
      }
      function ajaxCall(url, callback) {
        var ajax=new XMLHttpRequest();
        ajax.onreadystatechange = function() {
          if (this.readyState == 4 && this.status == 200) {
            callback(JSON.parse(this.responseText));
          }
        };
        ajax.open("GET", url, true);
        ajax.send();
      }
      function addCountries(obj) {
          var select = document.getElementById('paises');
          select.innerHTML = "";
          var paises = obj.contenido;
          for (var key in paises) {
            var option = document.createElement('option');
            option.value = paises[key];
            option.innerHTML = key;
            select.appendChild(option);
          }
      }
    </script>

    <select id="paises">

    </select>

  </body>
</html>
