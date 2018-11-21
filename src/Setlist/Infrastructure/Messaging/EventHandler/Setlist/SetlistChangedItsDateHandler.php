<?php

namespace Setlist\Infrastructure\Messaging\EventHandler\Setlist;

use Setlist\Application\Persistence\Setlist\SetlistProjectorRepository;
use Setlist\Domain\Entity\Setlist\Event\SetlistChangedItsDate;

class SetlistChangedItsDateHandler
{
    private $setlistProjectionRepository;

    public function __construct(SetlistProjectorRepository $setlistProjectionRepository)
    {
        $this->setlistProjectionRepository = $setlistProjectionRepository;
    }

    public function __invoke(SetlistChangedItsDate $event)
    {
        $this->setlistProjectionRepository->changeDate($event);
    }
}
