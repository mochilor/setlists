<?php

namespace Setlist\Infrastructure\Messaging\EventHandler\Song;

use Setlist\Application\Persistence\Setlist\SetlistProjectorRepository;
use Setlist\Domain\Entity\Song\Event\SongWasUnhidden;

class SongWasUnhiddenHandler
{
    private $setlistProjectionRepository;

    public function __construct(SetlistProjectorRepository $setlistProjectionRepository)
    {
        $this->setlistProjectionRepository = $setlistProjectionRepository;
    }

    public function __invoke(SongWasUnhidden $songWasUnhidden)
    {
        $this->setlistProjectionRepository->unhideSongInSetlists($songWasUnhidden);
    }
}
