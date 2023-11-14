#!/bin/bash

# constroi a imagem, sobe o container e faz o setup do ambiente de desenvolvimento

APP_CONTAINER_NAME=laravue_api

docker-compose up -d || docker compose up -d

docker exec -it $APP_CONTAINER_NAME cp .env.example .env
docker exec -it $APP_CONTAINER_NAME php artisan key:generate
docker exec -it $APP_CONTAINER_NAME composer install
docker exec -it $APP_CONTAINER_NAME php artisan migrate --seed
