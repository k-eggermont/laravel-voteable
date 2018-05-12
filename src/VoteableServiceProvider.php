<?php

namespace Keggermont\Voteable;

use Illuminate\Support\ServiceProvider;

class VoteableServiceProvider extends ServiceProvider {
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot() {

        $this->publishes([__DIR__ . '/../database/migrations/create_votes_table.php' => database_path('migrations/' . date('Y_m_d_His', time()) . '_create_votes_table.php'),], 'migrations');
        $this->publishes([__DIR__ . '/../config/laravel-voteable.php' => config_path('laravel-voteable.php'),], 'config');
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register() {
        $this->mergeConfigFrom(__DIR__ . '/../config/laravel-voteable.php', 'laravel-voteable');

    }

}