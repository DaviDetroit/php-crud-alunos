FROM php:8.2-apache

# Remove outros MPMs que possam causar conflito
RUN a2dismod mpm_event mpm_worker || true

# Instala PDO MySQL
RUN docker-php-ext-install pdo pdo_mysql

# Copia o projeto
COPY . /var/www/html/

EXPOSE 80
