<?php

namespace Setlist\Application\Query\Setlist\Handler;

use Setlist\Application\DataTransformer\SetlistDataTransformer;
use Setlist\Application\Exception\SetlistDoesNotExistException;
use Setlist\Application\Persistence\Setlist\PersistedSetlist;
use Setlist\Application\Persistence\Setlist\PersistedSetlistRepository as ApplicationSetlistRepository;
use Setlist\Application\Query\Setlist\GetSetlist;
use Setlist\Domain\Value\Uuid;

class GetSetlistHandler
{
    private $setlistRepository;
    private $setlistDataTransformer;

    public function __construct(ApplicationSetlistRepository $setlistRepository, SetlistDataTransformer $setlistDataTransformer)
    {
        $this->setlistRepository = $setlistRepository;
        $this->setlistDataTransformer = $setlistDataTransformer;
    }

    public function __invoke(GetSetlist $query)
    {
        $uuid = Uuid::create($query->uuid());
        $setlist = $this->setlistRepository->getOneSetlistById($uuid);

        if (!$setlist instanceof PersistedSetlist) {
            throw new SetlistDoesNotExistException('Setlist not found');
        }

        $this->setlistDataTransformer->write($setlist);

        return $this->setlistDataTransformer->read();
    }
}
