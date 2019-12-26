FROM php:7.3.2-apache-stretch

LABEL maintainer="Kevin Padillo <kevin.padillo@decathlon.com>" \
      version="1.0"

COPY --chown=www-data:www-data . /srv/app

RUN chown -R www-data:www-data \
        /srv/app/storage \
        /srv/app/bootstrap/cache

COPY build_config/vhost.conf /etc/apache2/sites-available/000-default.conf 
# COPY build_config/certs/fullchain.pem /opt/certs/ecommerce-api/fullchain.pem
# COPY build_config/certs/privkey.pem /opt/certs/ecommerce-api/privkey.pem

# RUN a2enmod ssl

WORKDIR /srv/app

RUN docker-php-ext-install mbstring pdo pdo_mysql \ 
    && a2enmod rewrite negotiation \
    && docker-php-ext-install opcache
    
# FROM php:7.2-fpm

# COPY composer.lock composer.json /var/www/

# COPY database /var/www/database

# WORKDIR /var/www

# RUN apt-get update && apt-get -y install git && apt-get -y install zip

# RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" \
#     && php -r "if (hash_file('SHA384', 'composer-setup.php') === 'a5c698ffe4b8e849a443b120cd5ba38043260d5c4023dbf93e1558871f1f07f58274fc6f4c93bcfd858c6bd0775cd8d1') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;" \
#     && php composer-setup.php \
#     && php -r "unlink('composer-setup.php');" \
#     && php composer.phar install --no-dev --no-scripts \
#     && rm composer.phar

# COPY . /var/www

# RUN chown -R www-data:www-data \
#         /var/www/storage \
#         /var/www/bootstrap/cache

# RUN  apt-get install -y libmcrypt-dev \
#         libmagickwand-dev --no-install-recommends \
#         && pecl install mcrypt-1.0.2 \
#         && docker-php-ext-install pdo_mysql \
#         && docker-php-ext-enable mcrypt

# RUN mv .env.prod .env

# RUN php artisan optimize

# CMD php artisan serve --host=0.0.0.0 --port=8181

# EXPOSE 8181