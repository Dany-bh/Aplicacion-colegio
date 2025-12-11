# Usar la imagen base oficial de Render para PHP con Apache
FROM render/php-apache

# Copiar todos los archivos del repositorio al directorio web del contenedor
COPY . /var/www/html
