# demo-zuralski-schibsted-app - PHP Developer Assignment. 

Project based on Symfony 3.

Host for docker on 32bit machine, so image has also 32bit.

# Features:
  - default lat and lng are coordinates of Neptune's Fountain
  - 2nd Richardson   Maturity   Model
  - default responses in JSON
  - application suited for horizontal scaling:
    - logs to syslog
    - cache to redis
    - session to memcached
  - POI searching
  - additional response format in XML
  - auto­generated API documentation - URI: /api/rest/doc
  - GUI - URI:/

## Running application

### On local machine like Debian with Apache 2

1. move application from repository to applications folder: `mv demo-zuralski-schibsted-app /srv/www/demo-zuralski-schibsted-app`
2. add entry in hosts to be able to access defined host`sudo echo "127.0.1.1	demo-zuralski-schibsted-app.dev demo-zuralski-schibsted-app.prod" >> /etc/hosts`
3. add Apache virtual host definition `cp  /srv/www/demo-zuralski-schibsted-app/.config/apache/apache.vhost.conf /etc/apache2/sites-available/demo-zuralski-schibsted-app.vhost.conf` 
4. enable new host in Apache `a2ensite demo-zuralski-schibsted-app.vhost.conf`
5. install depenties: `curl -sS https://getcomposer.org/installer | php && mv composer.phar composer && composer update` 
6. build application for dev environment: `bin/phing build:dev` or build it for production environment: `bin/phing build:prod`
7. Run in a browser `http://demo-zuralski-schibsted-app.dev/` or `http://demo-zuralski-schibsted-app.dev//api/rest/doc` for api documentation 

### On local machine like Debian with Nginx 
(assuming that php-fpm or php-cgi is listening on 127.0.0.1:9000)

1. move application from repository to applications folder: `mv demo-zuralski-schibsted-app /srv/www/demo-zuralski-schibsted-app`
2. add entry in hosts to be able to access defined host`sudo echo "127.0.1.1	demo-zuralski-schibsted-app.dev demo-zuralski-schibsted-app.prod" >> /etc/hosts`
3. add Nginx virtual host definition `cp  /srv/www/demo-zuralski-schibsted-app/.config/nginx/nginx-vhost.conf /etc/nginx/sites-enabled/nginx-vhost.conf` 
4.  reload Nginx vhost definition `/etc/init.d/nginx reload`
5. install depenties: `curl -sS https://getcomposer.org/installer | php && mv composer.phar composer && composer update` 
6. build application for dev environment: `bin/phing build:dev` or build it for production environment: `bin/phing build:prod`
7. Run in a browser `http://demo-zuralski-schibsted-app.dev/` or `http://demo-zuralski-schibsted-app.dev//api/rest/doc` for api documentation 

