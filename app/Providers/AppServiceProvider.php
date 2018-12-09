<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Setlist\Infrastructure\Messaging\CommandBus;
use Setlist\Infrastructure\Messaging\QueryBus;

class AppServiceProvider extends ServiceProvider
{
    private $driver;

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->setDriver(env('DB_DRIVER'));
        $this->registerSingletons();
        $this->registerDomainRepositories();
        $this->registerApplicationRepositories();
        $this->registerDataTransformers();
    }

    private function setDriver($driver)
    {
        if (strtolower($driver) == 'eloquent') {
            $this->driver = 'Eloquent';
        } elseif (strtolower($driver) == 'pdo') {
            $this->driver = 'PDO';
        }
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
            "\Setlist\Infrastructure\Repository\Domain\\$this->driver\SongRepository"
        );

        $this->app->bind(
            \Setlist\Domain\Entity\Song\SongTitleRepository::class,
            "\Setlist\Infrastructure\Repository\Domain\\$this->driver\SongTitleRepository"
        );

        $this->app->bind(
            \Setlist\Domain\Entity\Setlist\SetlistRepository::class,
            "\Setlist\Infrastructure\Repository\Domain\\$this->driver\SetlistRepository"
        );

        $this->app->bind(
            \Setlist\Domain\Entity\Setlist\SetlistNameRepository::class,
            "\Setlist\Infrastructure\Repository\Domain\\$this->driver\SetlistNameRepository"
        );
    }

    private function registerApplicationRepositories()
    {
        $this->app->bind(
            \Setlist\Application\Persistence\Song\PersistedSongRepository::class,
            "\Setlist\Infrastructure\Repository\Application\\$this->driver\PersistedSongRepository"
        );

        $this->app->bind(
            \Setlist\Application\Persistence\Setlist\SetlistRepository::class,
            "\Setlist\Infrastructure\Repository\Application\\$this->driver\SetlistRepository"
        );

        $this->app->bind(
            \Setlist\Application\Persistence\Setlist\PersistedSetlistRepository::class,
            env('PROJECTIONS') ?
                \Setlist\Infrastructure\Repository\Application\Eloquent\SetlistProjectionRepository::class :
                "\Setlist\Infrastructure\Repository\Application\\$this->driver\PersistedSetlistRepository"
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
