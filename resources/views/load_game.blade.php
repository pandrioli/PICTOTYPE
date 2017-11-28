<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>PictoType</title>
  </head>
  <body>
    <script type="text/javascript">
      location.reload(true);
      ajax = new XMLHttpRequest();
      ajax.onload = function() {
        document.body.parentNode.innerHTML = this.responseText;
      }
      ajax.open("GET", "/gameplay/{{$id}}", true);
      ajax.send();
    </script>
  </body>
</html>
