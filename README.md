

## Made from Chirper

Chirper is a simple Twitter clone coming from Laravel Bootcamp built with Laravel, Vite, AplineJS, and Tailwind CSS. It serves as a demonstration of how to build a modern web application using these technologies.

- `composer create-project laravel/laravel my-project`
  - `cd chirper`
- `composer require laravel/breeze --dev`
- `php artisan breeze:install blade`
- `npm install`
- `php artisan migrate`
- `npm run dev`
- `php artisan serve`

This set of commands will set up a new Laravel project, install the Breeze starter kit for authentication, and run the application locally.

Before migrating, you may want to set up your `.env` file with the appropriate database connection settings.

Generate application key with this command: `php artisan key:generate`.

## Requirements
- PHP 8.2 - 8.4
- ...

## Install for development
- clone this repo
- create db
- `npm install`
- `composer install`
- copy `.env.example` to `.env`
    - set up your database connection in `.env` (DB_NAME, DB_USER, DB_PASSWORD)
- `npm run dev` 
- `php artisan serve`

## Deployment
- clone this repo `git clone --depth=1 https://github.com/.../repo.git level-up`
- `cd "level-up"`
- set up your `.env` file (APP_ENV=production, APP_DEBUG=false, DB_NAME, DB_USER, DB_PASSWORD, etc.)
- (pull `git pull origin main`)
- install `composer install --optimize-autoloader --no-dev`
- migrate `php artisan migrate --force`
- clear caches
  - `php artisan cache:clear`
  - `php artisan config:clear`
  - `php artisan route:clear`
  - `php artisan view:clear`
- rebuild caches
    - `php artisan config:cache`
    - `php artisan route:cache`
    - `php artisan view:cache`
- `npm install`
- `npm run build` or `npm run prod`

## Documentation

- [Laravel docs](https://laravel.com/docs/12.x)
- [Bootcamp (wayback)](https://web.archive.org/web/20231224155728/https://bootcamp.laravel.com/blade/installation)
- 

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
