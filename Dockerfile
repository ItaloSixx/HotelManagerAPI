# Use a imagem oficial do PHP 8.2 com Apache
FROM php:8.2-apache

# Instala dependências necessárias
RUN apt-get update && apt-get install -y \
    zip \
    unzip \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libonig-dev \
    libxml2-dev \
    curl \
    && docker-php-ext-configure gd \
    && docker-php-ext-install pdo pdo_mysql mbstring exif pcntl bcmath gd

# Instala o Composer
COPY --from=composer:2.6 /usr/bin/composer /usr/bin/composer

# Ativa o módulo rewrite do Apache
RUN a2enmod rewrite

# Define o ServerName para evitar avisos do Apache
RUN echo 'ServerName localhost' >> /etc/apache2/apache2.conf

# Define o diretório de trabalho
WORKDIR /var/www/html

# Dá permissão ao diretório de trabalho
RUN chown -R www-data:www-data /var/www/html && chmod -R 755 /var/www/html

# Cria um link simbólico para a pasta public
RUN ln -s /var/www/html/public /var/www/html/public_html

# Exponha a porta 80
EXPOSE 80

# Comando para rodar o Apache (default do php:apache)
CMD ["apache2-foreground"]
