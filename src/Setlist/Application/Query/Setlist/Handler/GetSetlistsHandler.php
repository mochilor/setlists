<?php

namespace Setlist\Application\Query\Setlist\Handler;

use Setlist\Application\DataTransformer\SetlistDataTransformer;
use Setlist\Application\Persistence\Setlist\PersistedSetlistRepository as ApplicationSetlistRepository;
use Setlist\Application\Query\Setlist\GetSetlists;

class GetSetlistsHandler
{
    private $applicationSetlistRepository;
    private $setlistDataTransformer;

    public function __construct(
        ApplicationSetlistRepository $applicationSetlistRepository,
        SetlistDataTransformer $setlistDataTransformer
    ) {
        $this->applicationSetlistRepository = $applicationSetlistRepository;
        $this->setlistDataTransformer = $setlistDataTransformer;
    }

    public function __invoke(GetSetlists $query)
    {
        $setlists = $this->applicationSetlistRepository->getAllSetlists($query->start(), $query->length(), $query->name());

        $transformedSetlists = [];
        foreach ($setlists as $setlist) {
            $this->setlistDataTransformer->write($setlist);
            $transformedSetlists[] = $this->setlistDataTransformer->read();
        }

        return $transformedSetlists;
    }
}
