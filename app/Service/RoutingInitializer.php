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
use Setlist\Infrastructure\Messaging\CommandBus;

class RoutingInitializer
{
    private $commandBus;

    public function __construct(CommandBus $commandBus)
    {
        $this->commandBus = $commandBus;
    }

    public function handle()
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
}