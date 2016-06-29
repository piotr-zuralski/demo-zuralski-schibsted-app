# host in 32bit so image also, sorry
FROM 32bit/debian

MAINTAINER Piotr Żuralski

# Let the conatiner know that there is no tty
ENV DEBIAN_FRONTEND noninteractive

### upgrade packages
RUN apt-get update
RUN apt-get upgrade -y

### install packages
RUN apt-get -y install nginx redis-server memcached curl php5 php5-cli php5-fpm php5-redis php5-memcached php5-curl

### cleanup
RUN apt-get autoclean
RUN apt-get clean
RUN apt-get autoremove

ENV APP_NAME demo-zuralski-schibsted-app
ENV WORKDIR /srv/www/"$APP_NAME"

COPY . "$WORKDIR"
COPY ./.config/nginx/nginx-fastcgi.conf /etc/nginx/conf.d/nginx-fastcgi.conf
COPY ./.config/nginx/nginx-vhost.conf /etc/nginx/sites-enabled/nginx-vhost.conf
WORKDIR "$WORKDIR"
ADD ./.config/php.ini "$WORKDIR"/php.ini

RUN curl -sS https://getcomposer.org/installer | php && mv composer.phar /usr/bin/composer

### due to error in clearing cache after installing packages, making it 3 step
RUN cd "$WORKDIR" && chmod -R 777 "$WORKDIR"/var && composer update --no-interaction --no-scripts
RUN rm -Rf "$WORKDIR"/var/dev/logs/* "$WORKDIR"/var/dev/cache/* "$WORKDIR"/var/prod/logs/* "$WORKDIR"/var/prod/cache/*
RUN cd "$WORKDIR" && chmod -R 777 "$WORKDIR"/var && composer run-script post-update-cmd

EXPOSE 80

CMD /sbin/init -D FOREGROUND
CMD /etc/init.d/php5-fpm start && /etc/init.d/nginx start

