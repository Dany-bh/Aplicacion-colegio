# Usar la imagen oficial de PHP 8.2 con Apache desde Docker Hub
FROM php:8.2-apache

# Copiar todos los archivos del repositorio al directorio web del contenedor
COPY . /var/www/html
