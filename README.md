## ORDER-SERVICE

### How to set up a project

#### To run the project working Docker is required

Spawn container and setup project without local composer-binaries
```shell
git clone git@github.com:zemlyannikova/order-service.git
cd order-service
docker-compose up -d
docker run -it --rm -v ${PWD}/:/var/www -w /var/www composer install
docker compose exec php-fpm php /var/www/bin/console doctrine:migrations:migrate --no-interaction
docker run --name node --rm -v ${PWD}/:/var/www -w /var/www -i node:18 yarn install
docker run --name node --rm -v ${PWD}/:/var/www -w /var/www -i node:18 yarn run encore dev
```
Then the project will be accessible via http://localhost:8084/products

### Run tests
```shell
docker compose exec php-fpm /var/www/vendor/phpunit/phpunit/phpunit --configuration /var/www/phpunit.xml.dist
```

### Technical information
Now to emulate that a delivery address is in a member state of the European Union the value "EU" should be entered in 
the address field.