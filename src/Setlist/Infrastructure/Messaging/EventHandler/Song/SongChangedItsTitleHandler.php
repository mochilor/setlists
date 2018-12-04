<?php

namespace Setlist\Infrastructure\Messaging\EventHandler\Song;

use Setlist\Application\Persistence\Setlist\SetlistProjectorRepository;
use Setlist\Domain\Entity\Song\Event\SongChangedItsTitle;

class SongChangedItsTitleHandler
{
    private $setlistProjectionRepository;

    public function __construct(SetlistProjectorRepository $setlistProjectionRepository)
    {
        $this->setlistProjectionRepository = $setlistProjectionRepository;
    }

    public function __invoke(SongChangedItsTitle $songChangedItsTitle)
    {
        $this->setlistProjectionRepository->changeSongTitleInSetlists($songChangedItsTitle);
    }
}
