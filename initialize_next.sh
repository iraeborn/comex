#!/bin/bash
if [ ! -d ./src/web ]; then
  mkdir -p ./src/web
fi
rm -rf ./src/web/.* 2>&1 > /dev/null
rm -rf ./src/web/* 2>&1 > /dev/null 

docker-compose run node npx create-next-app /var/www/web
docker-compose run node npm --cwd /var/www/web run dev

touch ./src/web/.gitkeep
