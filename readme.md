PICTOTYPE

Pasos para instalar 

* desde la consola en un directorio con permiso de escritura:

git clone https://github.com/pandrioli/PICTOTYPE.git<br>
cd PICTOTYPE <br>
composer install <br>
php artisan key:generate <br>
cp .env.example .env <br>

* Crear un schema en MySQL llamado "pictotype"

* editar el archivo .env para modificar los seteos del servidor MySQL

php artisan migrate <br>
php artisan serve <br>

* ir a localhost:8000 en el navegador
