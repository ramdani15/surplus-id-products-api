## Surplus Indonesia Products API

Surplus Indonesia Products API.


## Features

- CRUD Categories and Products
- Swagger API Documentation (`/api/{VERSION}/documentation`)


## Installation Guide

- Clone repo with `git clone https://github.com/ramdani15/surplus-id-products-api.git`
- Run `composer install`
- Copy `env.example` to `.env` and set it with your own credentials
- For testing copy `env.example` to `.env.testing` and set it with your own credentials
- Run `php artisan migrate:refresh --seed`
- Run `php artisan key:generate`
- Run testing `php artisan test`
- Run `php artisan serve`
- Open [http://localhost:8000](http://localhost:8000)
- Open [http://localhost:8000/api/v1/documentation](http://localhost:8000/api/v1/documentation) for Swagger


## Installation Guide (Docker Compose)

- Clone repo with `git clone https://github.com/ramdani15/surplus-id-products-api.git`
- Copy `env.example` to `.env` and set it with your own credentials
- For testing copy `env.example` to `.env.testing` and set it with your own credentials
- Copy `/docker/env.example` to `/docker/.env` and set it with your own credentials
- Run `docker compose up -d --build`
- Run `docker compose exec app composer install`
- Run `docker compose exec app php artisan migrate:refresh --seed`
- Run `docker compose exec app php artisan key:generate`
- Run testing `docker compose exec app php artisan test`
- On `.env` set `DB_HOST=surplus-id-db` and `DB_PORT=3306`
- Open [http://localhost:8080](http://localhost:8080)
- Open [http://localhost:8000/api/v1/documentation](http://localhost:8000/api/v1/documentation) for Swagger


### Notes
- Code style fixer with [Laravel Pint](https://github.com/laravel/pint) RUN : `sh pint.sh` or `docker compose exec app sh pint.sh` for Docker.
