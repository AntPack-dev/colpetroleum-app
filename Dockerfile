FROM php:7.1-fpm

# Install dependencies
RUN apt-get update \
  && apt-get install -y zlib1g-dev libicu-dev wget gnupg g++ git openssh-client \
  && apt-get install -y libxml2-dev libfreetype6-dev libpng-dev libj peg-dev libzip-dev \
&& apt-get install -y libmagickwand-dev unzip\
  && docker-php-ext-configure intl \
  && docker-php-ext-install intl pdo_mysql zip mysqli

# clean
RUN rm -rf /var/cache/apk/*

# Install php extensions.
RUN docker-php-ext-configure gd --with-freetype=/usr/include/ --with-jpeg=/usr/include
RUN docker-php-ext-install bcmath intl zip pcntl soap gd

# Enable imagick
RUN pecl install imagick-3.4.3
RUN echo "extension=imagick.so" >> /usr/local/etc/php/conf.d/imagick.ini

# Install composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# agregar usuario para la aplicación laravel
RUN groupadd -g 1000 www
RUN useradd -u 1000 -ms /bin/bash -g www www

# Copiar el directorio existente a /var/www
COPY . /var/www

# copiar los permisos del directorio de la aplicación
COPY --chown=www:www . /var/www

# cambiar el usuario actual por www
USER www

# exponer el puerto 9000 e iniciar php-fpm server
EXPOSE 9000
CMD ["php-fpm"]
