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
            \Setlist\Application\Persistence\Song\PersistedSongRepository::class,
            \Setlist\Infrastructure\Repository\Application\InMemory\PersistedSongRepository::class
        );

        $this->app->bind(
            \Setlist\Domain\Entity\Setlist\SetlistRepository::class,
            \Setlist\Infrastructure\Repository\Domain\InMemory\SetlistRepository::class
        );

        $this->app->bind(
            \Setlist\Application\Persistence\Setlist\PersistedSetlistRepository::class,
            \Setlist\Infrastructure\Repository\Application\InMemory\PersistedSetlistRepository::class
        );

        $this->app->bind(
            \Setlist\Domain\Entity\Song\SongAvailabilityRepository::class,
            \Setlist\Infrastructure\Repository\Domain\InMemory\SongAvailabilityRepository::class
        );

        $this->app->bind(
            \Setlist\Domain\Entity\Setlist\SetlistAvailabilityRepository::class,
            \Setlist\Infrastructure\Repository\Domain\InMemory\SetlistAvailabilityRepository::class
        );
    }
}
