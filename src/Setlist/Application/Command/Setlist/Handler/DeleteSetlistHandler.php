<?php

namespace Setlist\Application\Command\Setlist\Handler;

use Setlist\Application\Command\Setlist\DeleteSetlist;
use Setlist\Application\Exception\SetlistDoesNotExistException;
use Setlist\Domain\Entity\Setlist\Setlist;
use Setlist\Domain\Entity\Setlist\SetlistRepository;
use Setlist\Domain\Value\UuidGenerator;

class DeleteSetlistHandler
{
    private $setlistRepository;
    private $uuidGenerator;

    public function __construct(SetlistRepository $setlistRepository, UuidGenerator $uuidGenerator) {
        $this->setlistRepository = $setlistRepository;
        $this->uuidGenerator = $uuidGenerator;
    }

    public function __invoke(DeleteSetlist $command)
    {
        $uuid = $this->uuidGenerator->fromString($command->uuid());
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
