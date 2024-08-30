#!/bin/bash

if [ ! -f "vendor/autoload.php"]; then
    composer install --no-progress --no-interaction
fi

if [ ! -f ".env" ]; then
    echo "Creating $APP_ENV environment file"
    cp .env.example .env
else
    echo "Using existing .env file"
fi

php artisan migrate
php artisan key:generate
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

php artisan serve --port=$PORT --host=0.0.0.0 --env=.env
exec docker-php-entrypoint "$@"