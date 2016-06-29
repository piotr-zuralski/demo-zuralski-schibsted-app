# [demo-zuralski-schibsted-app](http://localhost:8080/) - PHP Developer Assignment. 

Project based on Symfony 3.

Host for docker on 32bit machine, so image has also 32bit.

Features:
  - default lat and lng are coordinates of Neptune's Fountain
  - 2nd Richardson   Maturity   Model
  - default responses in JSON
  - automated tests
  - application suited for horizontal scaling:
    - logs to syslog
    - cache to redis
    - session to memcached
  - POI searching
  - additional response format in XML
  - auto­generated API documentation [/api/rest/doc](http://localhost:8080/api/rest/doc)
  - GUI [/](http://localhost:8080/)

## Running application

for dev env:
```bash
sudo docker build -t demo-zuralski-schibsted-app .
sudo docker run -it -d -p 127.0.0.1:8080:80 demo-zuralski-schibsted-app /bin/sh -c "cd /srv/www/demo-zuralski-schibsted-app; bin/phing build:dev;"
```

for prod env:
```bash
sudo docker build -t demo-zuralski-schibsted-app .
sudo docker run -it -d -p 127.0.0.1:8080:80 demo-zuralski-schibsted-app /bin/sh -c "cd /srv/www/demo-zuralski-schibsted-app; bin/phing build:prod;"
```
