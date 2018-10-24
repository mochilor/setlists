<?php

namespace App\Service;

use Setlist\Application\Command\Setlist\CreateSetlist;
use Setlist\Application\Command\Setlist\DeleteSetlist;
use Setlist\Application\Command\Setlist\Handler\CreateSetlistHandler;
use Setlist\Application\Command\Setlist\Handler\DeleteSetlistHandler;
use Setlist\Application\Command\Setlist\Handler\UpdateSetlistHandler;
use Setlist\Application\Command\Setlist\UpdateSetlist;
use Setlist\Application\Command\Song\CreateSong;
use Setlist\Application\Command\Song\DeleteSong;
use Setlist\Application\Command\Song\Handler\CreateSongHandler;
use Setlist\Application\Command\Song\Handler\DeleteSongHandler;
use Setlist\Application\Command\Song\Handler\UpdateSongHandler;
use Setlist\Application\Command\Song\UpdateSong;
use Setlist\Application\Query\Setlist\GetSetlist;
use Setlist\Application\Query\Setlist\GetSetlists;
use Setlist\Application\Query\Setlist\Handler\GetSetlistHandler;
use Setlist\Application\Query\Setlist\Handler\GetSetlistsHandler;
use Setlist\Application\Query\Song\GetSong;
use Setlist\Application\Query\Song\GetSongs;
use Setlist\Application\Query\Song\Handler\GetSongHandler;
use Setlist\Application\Query\Song\Handler\GetSongsHandler;
use Setlist\Domain\Entity\EventBus;
use Setlist\Domain\Entity\Song\Event\SongWasCreated;
use Setlist\Infrastructure\Messaging\CommandBus;
use Setlist\Infrastructure\Messaging\EventHandler\Song\SongWasCreatedHandler;
use Setlist\Infrastructure\Messaging\QueryBus;

class RoutingInitializer
{
    private $commandBus;
    private $eventBus;
    private $queryBus;

    public function __construct(CommandBus $commandBus, EventBus $eventBus, QueryBus $queryBus)
    {
        $this->commandBus = $commandBus;
        $this->eventBus = $eventBus;
        $this->queryBus = $queryBus;
    }

    public function init()
    {
        $this->initCommands();
        $this->initEvents();
        $this->initQueries();
    }

    private function initCommands()
    {
        // Song
        $this->commandBus->addHandler(CreateSong::class, app(CreateSongHandler::class));
        $this->commandBus->addHandler(UpdateSong::class, app(UpdateSongHandler::class));
        $this->commandBus->addHandler(DeleteSong::class, app(DeleteSongHandler::class));

        // Setlist
        $this->commandBus->addHandler(CreateSetlist::class, app(CreateSetlistHandler::class));
        $this->commandBus->addHandler(UpdateSetlist::class, app(UpdateSetlistHandler::class));
        $this->commandBus->addHandler(DeleteSetlist::class, app(DeleteSetlistHandler::class));
    }

    private function initEvents()
    {
        // Song
        $this->eventBus->addHandler(SongWasCreated::class, app(SongWasCreatedHandler::class));
    }

    private function initQueries()
    {
        // Song
        $this->queryBus->addHandler(GetSong::class, app(GetSongHandler::class));
        $this->queryBus->addHandler(GetSongs::class, app(GetSongsHandler::class));

        // Setlit
        $this->queryBus->addHandler(GetSetlist::class, app(GetSetlistHandler::class));
        $this->queryBus->addHandler(GetSetlists::class, app(GetSetlistsHandler::class));
    }
}