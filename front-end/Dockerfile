FROM node:lts-alpine

WORKDIR /usr/laravue_app_frontend

RUN npm install -g http-server
RUN npm install -g @vue/cli

COPY ./app/package*.json ./

RUN npm i

COPY ./app/ ./

EXPOSE 3000

CMD [ "npm", "run", "dev" ]