<?php

namespace Setlist\Application\Command\Setlist\Handler;

use Setlist\Application\Command\Setlist\DeleteSetlist;
use Setlist\Application\Exception\SetlistDoesNotExistException;
use Setlist\Application\Persistence\Setlist\SetlistRepository as ApplicationSetlistRespository;
use Setlist\Domain\Entity\Setlist\Setlist;
use Setlist\Domain\Entity\Setlist\SetlistRepository;
use Setlist\Domain\Value\Uuid;

class DeleteSetlistHandler
{
    private $setlistRepository;
    private $applicationSetlistRepository;

    public function __construct(
        SetlistRepository $setlistRepository,
        ApplicationSetlistRespository $applicationSetlistRepository
    ) {
        $this->setlistRepository = $setlistRepository;
        $this->applicationSetlistRepository = $applicationSetlistRepository;
    }

    public function __invoke(DeleteSetlist $command)
    {
        $uuid = Uuid::create($command->uuid());
        $setlist = $this->setlistRepository->get($uuid);

        $this->guard($setlist);

        $setlist->delete();

        $this->setlistRepository->save($setlist);
    }

    private function guard($setlist)
    {
        if (!$setlist instanceof Setlist) {
            throw new SetlistDoesNotExistException('Setlist not found');
        }
    }
}