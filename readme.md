<h1>PICTOTYPE</h1>

<h3>Instalacion</h3> 

<ul>
  <li>desde la consola en un directorio con permiso de escritura: <br><br>

git clone https://github.com/pandrioli/PICTOTYPE.git<br>
cd PICTOTYPE <br>
composer install <br>
cp .env.example .env <br><br>
php artisan key:generate <br>


</li>

<li>Crear un schema en MySQL llamado "pictotype"</li>

<li>editar el archivo .env para modificar los seteos del servidor MySQL</li>

<li>tipear en la consola<br><br>
php artisan migrate <br>
php artisan serve <br><br>
  </li>

<li> ir a localhost:8000 en el navegador
</li>
