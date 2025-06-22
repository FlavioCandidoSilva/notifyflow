FROM php:8.2-fpm

# Dependências do sistema
RUN apt-get update && apt-get install -y \
    build-essential \
    libpng-dev \
    libjpeg-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    zip \
    unzip \
    curl \
    git \
    gcc \
    make \
    autoconf \
    libc-dev \
    pkg-config

# Extensões PHP
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip

# Instalar Composer
RUN curl -sS https://getcomposer.org/installer | php && \
    mv composer.phar /usr/local/bin/composer

# Instalar Xdebug
RUN pecl install xdebug && docker-php-ext-enable xdebug

# Configuração Xdebug
RUN echo "zend_extension=$(find /usr/local/lib/php/extensions/ -name xdebug.so)" > /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
    && echo "xdebug.mode=debug" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
    && echo "xdebug.start_with_request=yes" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
    && echo "xdebug.client_host=host.docker.internal" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
    && echo "xdebug.client_port=9003" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
    && echo "xdebug.log=/tmp/xdebug.log" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini

# Diretório de trabalho
WORKDIR /var/www

# Copiar todos os arquivos do projeto
COPY . /var/www

# Permissões e comandos automáticos
RUN chown -R www-data:www-data /var/www \
    && chmod -R 775 /var/www/storage /var/www/bootstrap/cache \
    && composer install --ignore-platform-reqs --no-interaction --prefer-dist \
    && php artisan config:cache \
    && php artisan storage:link || true \
    && php artisan migrate --force || true

# Expor as portas
EXPOSE 8000 9003

# Comando padrão ao subir o container
CMD php artisan serve --host=0.0.0.0 --port=8000
