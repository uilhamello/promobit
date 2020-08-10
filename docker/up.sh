docker-compose up -d

docker exec -it lamp-userService composer install -y

docker exec -it lamp-userService ./bin/phpunit