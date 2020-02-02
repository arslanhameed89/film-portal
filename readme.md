# Film Portal
develop as starter movie portal


## Included
* [Laravel 6.0](https://laravel.com/docs/6.0)
* [Vue 2](https://vuejs.org)
* [Vue Router 3](http://router.vuejs.org)
* [Vuex 3](http://vuex.vuejs.org)
* [Axios](https://github.com/mzabriskie/axios)
* [Authentication with JWT Token](https://github.com/tymondesigns/jwt-auth)
* [Vuetify](https://vuetifyjs.com/en/getting-started/quick-start)

## Installation:
* Copy `.env.example` to `.env`
* Configure `.env`
* Configure for TDD `.env.testing`
* Run `composer install`
* Run `php artisan key:generate`
* Run `php artisan jwt:secret`
* Run `php artisan migrate`
* Run `npm install`
* Make sure `storage/framework/cache`, `storage/framework/sessions`, `storage/framework/views` directories exist. Run `mkdir -p storage/framework/{sessions,views,cache}`

## Usage
* Run `npm run watch` for live reloading using BrowserSync
* Run `npm run prod` for production buid
