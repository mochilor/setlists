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
            \Setlist\Infrastructure\Repository\Domain\InMemory\SongRepository::class
        );

        $this->app->bind(
            \Setlist\Application\Persistence\Song\SongRepository::class,
            \Setlist\Infrastructure\Repository\Application\InMemory\SongRepository::class
        );

        $this->app->bind(
            \Setlist\Domain\Entity\Setlist\SetlistRepository::class,
            \Setlist\Infrastructure\Repository\Domain\InMemory\SetlistRepository::class
        );

        $this->app->bind(
            \Setlist\Application\Persistence\Setlist\SetlistRepository::class,
            \Setlist\Infrastructure\Repository\Application\InMemory\SetlistRepository::class
        );
    }
}
