<?php

namespace Setlist\Infrastructure\Messaging\EventHandler\Setlist;

use Setlist\Application\Persistence\Setlist\SetlistProjectorRepository;
use Setlist\Domain\Entity\Setlist\Event\SetlistChangedItsDescription;

class SetlistChangedItsDescriptionHandler
{
    private $setlistProjectionRepository;

    public function __construct(SetlistProjectorRepository $setlistProjectionRepository)
    {
        $this->setlistProjectionRepository = $setlistProjectionRepository;
    }

    public function __invoke(SetlistChangedItsDescription $event)
    {
        $this->setlistProjectionRepository->changeDescription($event);
    }
}
