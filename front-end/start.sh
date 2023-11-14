#!/bin/bash

APP_CONTAINER_NAME=laravue_app_frontend
{
    docker-compose up -d
} || {
    docker compose up -d
}

docker exec -it $APP_CONTAINER_NAME cp .env.example .env