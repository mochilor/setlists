<?php

namespace Setlist\Infrastructure\Messaging\EventHandler\Song;

use Setlist\Application\Persistence\Setlist\SetlistProjectorRepository;
use Setlist\Domain\Entity\Song\Event\SongWasHidden;

class SongWasHiddenHandler
{
    private $setlistProjectionRepository;

    public function __construct(SetlistProjectorRepository $setlistProjectionRepository)
    {
        $this->setlistProjectionRepository = $setlistProjectionRepository;
    }

    public function __invoke(SongWasHidden $songWasHidden)
    {
        $this->setlistProjectionRepository->hideSongInSetlists($songWasHidden);
    }
}
