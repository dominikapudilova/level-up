

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

Generate appliation key with this command: `php artisan key:generate`.

## Documentation

- [Laravel docs](https://laravel.com/docs/12.x)
- [Bootcamp (wayback)](https://web.archive.org/web/20231224155728/https://bootcamp.laravel.com/blade/installation)
- 

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
