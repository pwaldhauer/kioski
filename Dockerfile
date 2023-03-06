FROM php:8.2.3-alpine3.17

COPY . /app

COPY .env.docker /app/.env

WORKDIR /app

RUN mkdir /app/storage
RUN mkdir /app/storage/app
RUN mkdir /app/storage/app/covers
RUN mkdir /app/storage/framework
RUN mkdir /app/storage/framework/cache
RUN mkdir /app/storage/framework/sessions
RUN mkdir /app/storage/framework/views
RUN mkdir /app/storage/logs

RUN touch /app/database.sqlite
RUN php artisan storage:link -n

CMD php artisan migrate -n && php artisan serve --host=0.0.0.0 --port=9901 -vvv
