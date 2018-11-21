<?php

namespace Setlist\Infrastructure\Messaging\EventHandler\Setlist;

use Setlist\Application\Persistence\Setlist\SetlistProjectorRepository;
use Setlist\Domain\Entity\Setlist\Event\SetlistWasDeleted;

class SetlistWasDeletedHandler
{
    private $setlistProjectionRepository;

    public function __construct(SetlistProjectorRepository $setlistProjectionRepository)
    {
        $this->setlistProjectionRepository = $setlistProjectionRepository;
    }

    public function __invoke(SetlistWasDeleted $event)
    {
        $this->setlistProjectionRepository->delete($event);
    }
}
