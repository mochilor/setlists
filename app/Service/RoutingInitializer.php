<?php

namespace App\Service;

use Setlist\Application\Command\Setlist\CreateSetlist;
use Setlist\Application\Command\Setlist\DeleteSetlist;
use Setlist\Application\Command\Setlist\Handler\CreateSetlistHandler;
use Setlist\Application\Command\Setlist\Handler\DeleteSetlistHandler;
use Setlist\Application\Command\Setlist\Handler\UpdateSetlistHandler;
use Setlist\Application\Command\Setlist\UpdateSetlist;
use Setlist\Application\Command\Song\CreateSong;
use Setlist\Application\Command\Song\ForceDeleteSong;
use Setlist\Application\Command\Song\Handler\CreateSongHandler;
use Setlist\Application\Command\Song\Handler\ForceDeleteSongHandler;
use Setlist\Application\Command\Song\Handler\UpdateSongHandler;
use Setlist\Application\Command\Song\UpdateSong;
use Setlist\Application\Query\Setlist\GetSetlist;
use Setlist\Application\Query\Setlist\GetSetlists;
use Setlist\Application\Query\Setlist\Handler\GetSetlistHandler;
use Setlist\Application\Query\Setlist\Handler\GetSetlistsHandler;
use Setlist\Application\Query\Song\GetSong;
use Setlist\Application\Query\Song\GetSongs;
use Setlist\Application\Query\Song\GetSongsByTitle;
use Setlist\Application\Query\Song\Handler\GetSongHandler;
use Setlist\Application\Query\Song\Handler\GetSongsByTitleHandler;
use Setlist\Application\Query\Song\Handler\GetSongsHandler;
use Setlist\Domain\Entity\EventBus;
use Setlist\Domain\Entity\Setlist\Event\SetlistChangedItsActCollection;
use Setlist\Domain\Entity\Setlist\Event\SetlistChangedItsDate;
use Setlist\Domain\Entity\Setlist\Event\SetlistChangedItsDescription;
use Setlist\Domain\Entity\Setlist\Event\SetlistChangedItsName;
use Setlist\Domain\Entity\Setlist\Event\SetlistWasCreated;
use Setlist\Domain\Entity\Setlist\Event\SetlistWasDeleted;
use Setlist\Domain\Entity\Song\Event\SongChangedItsTitle;
use Setlist\Domain\Entity\Song\Event\SongWasDeleted;
use Setlist\Domain\Entity\Song\Event\SongWasHidden;
use Setlist\Domain\Entity\Song\Event\SongWasUnhidden;
use Setlist\Infrastructure\Messaging\CommandBus;
use Setlist\Infrastructure\Messaging\EventHandler\Setlist\SetlistChangedItsActCollectionHandler;
use Setlist\Infrastructure\Messaging\EventHandler\Setlist\SetlistChangedItsDateHandler;
use Setlist\Infrastructure\Messaging\EventHandler\Setlist\SetlistChangedItsDescriptionHandler;
use Setlist\Infrastructure\Messaging\EventHandler\Setlist\SetlistChangedItsNameHandler;
use Setlist\Infrastructure\Messaging\EventHandler\Setlist\SetlistWasCreatedHandler;
use Setlist\Infrastructure\Messaging\EventHandler\Setlist\SetlistWasDeletedHandler;
use Setlist\Infrastructure\Messaging\EventHandler\Song\SongChangedItsTitleHandler;
use Setlist\Infrastructure\Messaging\EventHandler\Song\SongWasDeletedHandler;
use Setlist\Infrastructure\Messaging\EventHandler\Song\SongWasHiddenHandler;
use Setlist\Infrastructure\Messaging\EventHandler\Song\SongWasUnhiddenHandler;
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
        $this->commandBus->addHandler(ForceDeleteSong::class, app(ForceDeleteSongHandler::class));

        // Setlist
        $this->commandBus->addHandler(CreateSetlist::class, app(CreateSetlistHandler::class));
        $this->commandBus->addHandler(UpdateSetlist::class, app(UpdateSetlistHandler::class));
        $this->commandBus->addHandler(DeleteSetlist::class, app(DeleteSetlistHandler::class));
    }

    private function initEvents()
    {
        // Song
        $this->eventBus->addHandler(SongWasHidden::class, app(SongWasHiddenHandler::class));
        $this->eventBus->addHandler(SongWasUnhidden::class, app(SongWasUnhiddenHandler::class));
        $this->eventBus->addHandler(SongChangedItsTitle::class, app(SongChangedItsTitleHandler::class));
        $this->eventBus->addHandler(SongWasDeleted::class, app(SongWasDeletedHandler::class));

        // Setlist
        //if (env('PROJECTIONS')) {
            $this->eventBus->addHandler(SetlistWasCreated::class, app(SetlistWasCreatedHandler::class));
            $this->eventBus->addHandler(SetlistChangedItsName::class, app(SetlistChangedItsNameHandler::class));
            $this->eventBus->addHandler(SetlistChangedItsDescription::class, app(SetlistChangedItsDescriptionHandler::class));
            $this->eventBus->addHandler(SetlistChangedItsDate::class, app(SetlistChangedItsDateHandler::class));
            $this->eventBus->addHandler(SetlistChangedItsActCollection::class, app(SetlistChangedItsActCollectionHandler::class));
            $this->eventBus->addHandler(SetlistWasDeleted::class, app(SetlistWasDeletedHandler::class));
        //}
    }

    private function initQueries()
    {
        // Song
        $this->queryBus->addHandler(GetSong::class, app(GetSongHandler::class));
        $this->queryBus->addHandler(GetSongs::class, app(GetSongsHandler::class));
        $this->queryBus->addHandler(GetSongsByTitle::class, app(GetSongsByTitleHandler::class));

        // Setlit
        $this->queryBus->addHandler(GetSetlist::class, app(GetSetlistHandler::class));
        $this->queryBus->addHandler(GetSetlists::class, app(GetSetlistsHandler::class));
    }
}
