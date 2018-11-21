<?php

namespace Setlist\Infrastructure\Messaging\EventHandler\Setlist;

use Setlist\Application\Persistence\Setlist\SetlistProjectorRepository;
use Setlist\Domain\Entity\Setlist\Event\SetlistWasCreated;

class SetlistWasCreatedHandler
{
    private $setlistProjectionRepository;

    public function __construct(SetlistProjectorRepository $setlistProjectionRepository)
    {
        $this->setlistProjectionRepository = $setlistProjectionRepository;
    }

    public function __invoke(SetlistWasCreated $event)
    {
        $this->setlistProjectionRepository->save($event);
    }
}
