<?php

namespace App\Service;

use Setlist\Application\Command\CreateSong;
use Setlist\Application\Command\Handler\CreateSongHandler;
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
    }
}