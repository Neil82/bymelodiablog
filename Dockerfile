FROM php:8.4.7-fpm

# --------------------------
# Paquetes y extensiones PHP
# --------------------------
RUN apt-get update && apt-get install -y \
      git unzip \
      libzip-dev libonig-dev \
      libpng-dev libjpeg-dev libfreetype6-dev libwebp-dev \
  && docker-php-ext-configure gd --with-freetype --with-jpeg --with-webp \
  && docker-php-ext-install \
       pdo_mysql \
       zip \
       mbstring \
       gd \
  && pecl install apcu \
  && docker-php-ext-enable apcu

# --------------------------
# Composer estable
# --------------------------
RUN curl -sS https://getcomposer.org/installer | php -- --quiet \
  && mv composer.phar /usr/local/bin/composer

# Opcache básico
RUN { \
  echo "opcache.enable=1"; \
  echo "opcache.memory_consumption=128"; \
  echo "opcache.max_accelerated_files=10000"; \
  echo "opcache.validate_timestamps=1"; \
  echo "opcache.revalidate_freq=2"; \
} > /usr/local/etc/php/conf.d/opcache.ini

WORKDIR /var/www/bymelodia_blog
USER www-data

