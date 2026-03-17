FROM php:8.2-apache

# Instala PDO MySQL
RUN docker-php-ext-install pdo pdo_mysql

# Copia o projeto
COPY . /var/www/html/

EXPOSE 80
CMD ["apache2-foreground"]