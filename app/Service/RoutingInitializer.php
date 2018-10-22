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
use Setlist\Domain\Entity\EventBus;
use Setlist\Domain\Entity\Song\Event\SongWasCreated;
use Setlist\Infrastructure\Messaging\CommandBus;
use Setlist\Infrastructure\Messaging\EventHandler\Song\SongWasCreatedHandler;

class RoutingInitializer
{
    private $commandBus;
    private $eventBus;

    public function __construct(CommandBus $commandBus, EventBus $eventBus)
    {
        $this->commandBus = $commandBus;
        $this->eventBus = $eventBus;
    }

    public function init()
    {
        $this->initCommands();
        $this->initEvents();
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
}