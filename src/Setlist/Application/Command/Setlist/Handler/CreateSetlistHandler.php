<?php

namespace Setlist\Application\Command\Setlist\Handler;

use Setlist\Application\Command\Setlist\CreateSetlist;
use Setlist\Application\Command\Setlist\Handler\Helper\SetlistHandlerHelper;
use Setlist\Application\Exception\InvalidSetlistException;
use Setlist\Application\Exception\SetlistNameNotUniqueException;
use Setlist\Domain\Entity\Setlist\SetlistFactory;
use Setlist\Domain\Entity\Setlist\SetlistNameRepository;
use Setlist\Domain\Entity\Setlist\SetlistRepository;

class CreateSetlistHandler
{
    private $setlistNameRepository;
    private $setlistRepository;
    private $setlistFactory;
    private $setlistHandlerHelper;

    public function __construct(
        SetlistNameRepository $setlistNameRepository,
        SetlistRepository $setlistRepository,
        SetlistFactory $setlistFactory,
        SetlistHandlerHelper $setlistHandlerHelper
    ) {
        $this->setlistNameRepository = $setlistNameRepository;
        $this->setlistRepository = $setlistRepository;
        $this->setlistFactory = $setlistFactory;
        $this->setlistHandlerHelper = $setlistHandlerHelper;
    }

    public function __invoke(CreateSetlist $command)
    {
        $this->guard($command);

        $actsForSetlist = $this->setlistHandlerHelper->getActsForSetlist($command->acts());

        $setlist = $this->setlistFactory->make(
            $this->setlistRepository->nextIdentity(),
            $actsForSetlist,
            $command->name(),
            $command->date()
        );

        $this->setlistRepository->save($setlist);
    }

    private function guard(CreateSetlist $command)
    {
        if (!$this->setlistNameRepository->nameIsAvailable($command->name())) {
            throw new SetlistNameNotUniqueException();
        }

        $songs = [];
        foreach ($command->acts() as $act) {
            foreach ($act as $songUuid) {
                if (in_array($songUuid, $songs)) {
                    throw new InvalidSetlistException('Non unique song provided');
                }

                $songs[] = $songUuid;
            }
        }
    }
}
