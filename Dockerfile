# FROM php:5.6.12-fpm - host in 32bit so image also, sorry
FROM 32bit/ubuntu:14.04

# Let the conatiner know that there is no tty
ENV DEBIAN_FRONTEND noninteractive

RUN apt-get -y upgrade
RUN apt-get -y install nginx redis-server memcached php5 php5-cli php5-fpm php5-redis php5-memcached

ENV APP_NAME demo-zuralski-schibsted-app
ENV WORKDIR /srv/www/"$APP_NAME"

COPY . "$WORKDIR"
COPY ./.config/nginx/nginx-fastcgi.conf /etc/nginx/conf.d/nginx-fastcgi.conf
COPY ./.config/nginx/nginx-default_php_params.conf /etc/nginx/conf.d/nginx-default_php_params.conf
COPY ./.config/nginx/nginx-vhost.conf /etc/nginx/sites-enabled/nginx-vhost.conf
WORKDIR "$WORKDIR"
ADD ./.config/php.ini "$WORKDIR"/php.ini

RUN curl -sS https://getcomposer.org/installer | php \
    && mv composer.phar /usr/bin/composer

RUN cd "$WORKDIR" && chmod -R 777 "$WORKDIR"/var && composer update--no-interaction

EXPOSE 80