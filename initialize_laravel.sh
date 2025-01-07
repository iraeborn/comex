#!/bin/bash
if [ ! -d ./src/api ]; then
  mkdir -p ./src/api
fi
rm -rf ./src/api/.* 2>&1 > /dev/null
rm -rf ./src/api/* 2>&1 > /dev/null

docker-compose run php composer clear-cache
docker-compose run php composer global require "laravel/installer=~1.1"
docker-compose run php composer create-project --prefer-dist laravel/laravel /var/www/api "6.*"
touch ./src/api/.gitkeep
