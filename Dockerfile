FROM php:cli-alpine

LABEL maintainer="Yakup Arslan <me@yakupars.pw>"

COPY . /app

WORKDIR /app