# [demo-zuralski-schibsted-app](http://demo-zuralski-schibsted-app.dev/) - PHP Developer Assignment. 

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
  - auto­generated API documentation [/api/rest/doc](http://demo-zuralski-schibsted-app.dev/api/rest/doc)
  - GUI [/](http://demo-zuralski-schibsted-app.dev/)

## Running application

for dev env:
```bash
sudo docker build -t demo-zuralski-schibsted-app .
sudo docker exec -it demo-zuralski-schibsted-app bash -c "bin/phing build:dev"
sudo docker run -it demo-zuralski-schibsted-app -d -p 8080:80
```