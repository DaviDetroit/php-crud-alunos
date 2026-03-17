# Imagem oficial do PHP com Apache
FROM php:8.2-apache

# Habilita PDO MySQL
RUN docker-php-ext-install pdo pdo_mysql

# Copia o projeto para o Apache
COPY . /var/www/html/

# Porta que o Railway vai usar
EXPOSE 80

# Nenhum módulo extra do Apache é ativado → evita conflito MPM