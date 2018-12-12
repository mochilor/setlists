<?php

namespace Setlist\Application\Query\Setlist\Handler;

use Setlist\Application\DataTransformer\SetlistDataTransformer;
use Setlist\Application\Exception\SetlistDoesNotExistException;
use Setlist\Application\Persistence\Setlist\PersistedSetlist;
use Setlist\Application\Persistence\Setlist\PersistedSetlistRepository as ApplicationSetlistRepository;
use Setlist\Application\Query\Setlist\GetSetlist;
use Setlist\Domain\Value\UuidGenerator;

class GetSetlistHandler
{
    private $setlistRepository;
    private $setlistDataTransformer;
    private $uuidGenerator;

    public function __construct(
        ApplicationSetlistRepository $setlistRepository,
        SetlistDataTransformer $setlistDataTransformer,
        UuidGenerator $uuidGenerator
    ) {
        $this->setlistRepository = $setlistRepository;
        $this->setlistDataTransformer = $setlistDataTransformer;
        $this->uuidGenerator = $uuidGenerator;
    }

    public function __invoke(GetSetlist $query)
    {
        $uuid = $this->uuidGenerator->fromString($query->uuid());
        $setlist = $this->setlistRepository->getOneSetlistById($uuid->value());

        if (!$setlist instanceof PersistedSetlist) {
            throw new SetlistDoesNotExistException('Setlist not found');
        }

        $this->setlistDataTransformer->write($setlist);

        return $this->setlistDataTransformer->read();
    }
}
