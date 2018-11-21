<?php

namespace Setlist\Infrastructure\Messaging\EventHandler\Setlist;

use Setlist\Application\Persistence\Setlist\SetlistProjectorRepository;
use Setlist\Domain\Entity\Setlist\Event\SetlistChangedItsActCollection;

class SetlistChangedItsActCollectionHandler
{
    private $setlistProjectionRepository;

    public function __construct(SetlistProjectorRepository $setlistProjectionRepository)
    {
        $this->setlistProjectionRepository = $setlistProjectionRepository;
    }

    public function __invoke(SetlistChangedItsActCollection $event)
    {
        $this->setlistProjectionRepository->changeActCollection($event);
    }
}
