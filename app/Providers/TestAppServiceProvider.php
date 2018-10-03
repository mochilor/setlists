<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class TestAppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        // Common implementations
        $this->app->bind(
            \Setlist\Domain\Entity\Song\SongRepository::class,
            \Setlist\Infrastructure\Repository\InMemory\SongRepository::class
        );

        $this->app->bind(
            \Setlist\Application\Persistence\Song\ApplicationSongRepository::class,
            \Setlist\Infrastructure\Persistence\InMemory\ApplicationSongRepository::class
        );
    }
}
