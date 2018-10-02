<?php

namespace App\Service;

use Setlist\Application\Command\CreateSong;
use Setlist\Application\Command\DeleteSong;
use Setlist\Application\Command\Handler\CreateSongHandler;
use Setlist\Application\Command\Handler\DeleteSongHandler;
use Setlist\Application\Command\Handler\UpdateSongHandler;
use Setlist\Application\Command\UpdateSong;
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
        $this->commandBus->addHandler(CreateSong::class, app(CreateSongHandler::class));
        $this->commandBus->addHandler(UpdateSong::class, app(UpdateSongHandler::class));
        $this->commandBus->addHandler(DeleteSong::class, app(DeleteSongHandler::class));
    }
}