<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Setlist\Infrastructure\Messaging\CommandBus;
use Setlist\Infrastructure\Messaging\QueryBus;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->registerSingletons();
        $this->registerDomainRepositories();
        $this->registerApplicationRepositories();
        $this->registerDataTransformers();
    }

    private function registerSingletons()
    {
        $this->app->singleton(CommandBus::class);
        $this->app->singleton(QueryBus::class);
        $this->app->singleton(
            \Setlist\Domain\Entity\EventBus::class,
            \Setlist\Infrastructure\Messaging\EventBus::class
        );

        $this->app->singleton(\PDO::class, function () {
            return new \PDO(
                sprintf(
                    '%s:host=%s;dbname=%s;charset=utf8',
                    env('DB_CONNECTION'), env('DB_HOST'), env('DB_DATABASE')
                ),
                env('DB_USERNAME'),
                env('DB_PASSWORD')
            );
        });
    }

    private function registerDomainRepositories()
    {
        $this->app->bind(
            \Setlist\Domain\Entity\Song\SongRepository::class,
            \Setlist\Infrastructure\Repository\Domain\Eloquent\SongRepository::class
            //\Setlist\Infrastructure\Repository\Domain\PDO\SongRepository::class
        );
        $this->app->bind(
            \Setlist\Domain\Entity\Setlist\SetlistRepository::class,
            \Setlist\Infrastructure\Repository\Domain\Eloquent\SetlistRepository::class
            //\Setlist\Infrastructure\Repository\Domain\PDO\SetlistRepository::class
        );
    }

    private function registerApplicationRepositories()
    {
        $this->app->bind(
            \Setlist\Application\Persistence\Song\SongRepository::class,
            \Setlist\Infrastructure\Repository\Application\Eloquent\SongRepository::class
            //\Setlist\Infrastructure\Repository\Application\PDO\SongRepository::class
        );
        $this->app->bind(
            \Setlist\Application\Persistence\Setlist\SetlistRepository::class,
            (env('PROJECTIONS') ?
                \Setlist\Infrastructure\Repository\Application\Eloquent\SetlistProjectionRepository::class :
                \Setlist\Infrastructure\Repository\Application\Eloquent\SetlistRepository::class)
            //\Setlist\Infrastructure\Repository\Application\PDO\SetlistRepository::class
        );
        $this->app->bind(
            \Setlist\Application\Persistence\Setlist\SetlistProjectorRepository::class,
            \Setlist\Infrastructure\Repository\Application\Eloquent\SetlistProjectorRepository::class
        );
    }

    private function registerDataTransformers()
    {
        $this->app->bind(
            \Setlist\Application\DataTransformer\SongDataTransformer::class,
            \Setlist\Infrastructure\DataTransformer\SongDataTransformer::class
        );
        $this->app->bind(
            \Setlist\Application\DataTransformer\SetlistDataTransformer::class,
            \Setlist\Infrastructure\DataTransformer\SetlistDataTransformer::class
        );
    }
}
