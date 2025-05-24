# Use a imagem oficial do PHP com Apache
FROM php:8.1-apache

# Copia todos os arquivos do repositório para a pasta web do Apache
COPY . /var/www/html/

# Ativa o módulo rewrite do Apache (caso você use .htaccess)
RUN a2enmod rewrite

# Define permissões apropriadas para os arquivos
RUN chown -R www-data:www-data /var/www/html
