<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Setlist\Infrastructure\Messaging\CommandBus;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        // Singletons
        $this->app->singleton(CommandBus::class);

        $this->app->singleton(\PDO::class, function() {
            return new \PDO(
                sprintf(
                    '%s:host=%s;dbname=%s;charset=utf8',
                    env('DB_CONNECTION'), env('DB_HOST'), env('DB_DATABASE')
                ),
                env('DB_USERNAME'),
                env('DB_PASSWORD')
            );
        });

        // Common implementations
        $this->app->bind(
            \Setlist\Domain\Entity\Song\SongRepository::class,
            \Setlist\Infrastructure\Repository\Domain\PDO\SongRepository::class
        );

        $this->app->bind(
            \Setlist\Application\Persistence\Song\SongRepository::class,
            \Setlist\Infrastructure\Repository\Application\PDO\SongRepository::class
        );

        $this->app->bind(
            \Setlist\Domain\Entity\Setlist\SetlistRepository::class,
            \Setlist\Infrastructure\Repository\Domain\PDO\SetlistRepository::class
        );

        $this->app->bind(
            \Setlist\Application\Persistence\Setlist\SetlistRepository::class,
            \Setlist\Infrastructure\Repository\Application\PDO\SetlistRepository::class
        );
    }
}
