<?php

namespace Setlist\Infrastructure\Messaging\EventHandler\Song;

use Setlist\Application\Persistence\Setlist\SetlistProjectorRepository;
use Setlist\Domain\Entity\Song\Event\SongWasDeleted;

class SongWasDeletedHandler
{
    private $setlistProjectionRepository;

    public function __construct(SetlistProjectorRepository $setlistProjectionRepository)
    {
        $this->setlistProjectionRepository = $setlistProjectionRepository;
    }

    public function __invoke(SongWasDeleted $songWasDeleted)
    {
        $this->setlistProjectionRepository->deleteSongInSetlists($songWasDeleted);
    }
}
