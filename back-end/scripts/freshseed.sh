#!/bin/bash

# constroi a imagem, sobe o container e faz o setup do ambiente de desenvolvimento

APP_CONTAINER_NAME=laravue_api

docker exec -it $APP_CONTAINER_NAME php artisan migrate:fresh --seed
