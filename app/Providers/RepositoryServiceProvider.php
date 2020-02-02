<?php

namespace App\Providers;

use App\Contracts\FilmGenreRepository;
use App\Contracts\FilmRepository;
use App\Repositories\FilmGenreRepositoryEloquent;
use App\Repositories\FilmRepositoryEloquent;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->bind(FilmRepository::class, FilmRepositoryEloquent::class);
        $this->app->bind(FilmGenreRepository::class, FilmGenreRepositoryEloquent::class);
        //:end-bindings:
    }
}
