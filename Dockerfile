# Use a imagem oficial PHP 8.2 com Apache
FROM php:8.2-apache

# Evita conflito de MPM: desabilita event e habilita prefork
RUN a2dismod mpm_event \
    && a2enmod mpm_prefork

# Habilita mod_rewrite (útil para URLs amigáveis)
RUN a2enmod rewrite

# Instala extensões PHP necessárias (PDO + MySQL)
RUN docker-php-ext-install pdo pdo_mysql

# Copia o projeto para o diretório padrão do Apache
COPY . /var/www/html/

# Ajusta permissões (opcional, mas recomendado)
RUN chown -R www-data:www-data /var/www/html

# Expõe a porta 80
EXPOSE 80

# Comando padrão do container
CMD ["apache2-foreground"]