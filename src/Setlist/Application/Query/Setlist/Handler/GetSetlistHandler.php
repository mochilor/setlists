<?php

namespace Setlist\Application\Query\Setlist\Handler;

use Setlist\Application\DataTransformer\SetlistDataTransformer;
use Setlist\Application\Exception\SetlistDoesNotExistException;
use Setlist\Application\Query\Setlist\GetSetlist;
use Setlist\Domain\Entity\Setlist\Setlist;
use Setlist\Domain\Entity\Setlist\SetlistRepository;
use Setlist\Domain\Value\Uuid;

class GetSetlistHandler
{
    private $setlistRepository;
    private $setlistDataTransformer;

    public function __construct(SetlistRepository $setlistRepository, SetlistDataTransformer $setlistDataTransformer)
    {
        $this->setlistRepository = $setlistRepository;
        $this->setlistDataTransformer = $setlistDataTransformer;
    }

    public function __invoke(GetSetlist $query)
    {
        $uuid = Uuid::create($query->uuid());
        $setlist = $this->setlistRepository->get($uuid);

        if (!$setlist instanceof Setlist) {
            throw new SetlistDoesNotExistException('Setlist not found');
        }

        $this->setlistDataTransformer->write($setlist);

        return $this->setlistDataTransformer->read();
    }
}
