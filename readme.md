PICTOTYPE

Pasos para instalar 

* desde la consola en un directorio con permiso de escritura:

git clone https://github.com/pandrioli/PICTOTYPE.git
cd PICTOTYPE
composer install
php artisan key:generate
cp .env.example .env

* Crear un schema en MySQL llamado "pictotype"

* editar el archivo .env para modificar los seteos del servidor MySQL

php artisan migrate
php artisan serve

* ir a localhost:8000 en el navegador
