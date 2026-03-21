FROM php:8.3-apache

# Instalar extensões necessárias
RUN docker-php-ext-install pdo_mysql

# Copiar código da aplicação
COPY . /var/www/html

# Habilitar mod_rewrite para .htaccess
RUN a2enmod rewrite

# Expor porta 8080
EXPOSE 8080

# Comando de start
CMD ["apache2-foreground"]
