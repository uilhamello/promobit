# promobit

1. Clone the project:
   git clone git@github.com:uilhamello/promobit.git

2. get into the docker folder:
   cd promobit/docker/

3. run up.sh ( Obs: if the up.sh file is not executable, please execute this command before: chmod +x up.sh ):
   ./up.sh

The up.sh file will install the composer dependencies and the system tests.

To execute the Phpunit again, just run:
docker exec lamp-userService ./bin/phpunit
