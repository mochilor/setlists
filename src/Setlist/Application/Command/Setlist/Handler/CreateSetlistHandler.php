<?php

namespace Setlist\Application\Command\Setlist\Handler;

use Setlist\Application\Command\Setlist\CreateSetlist;
use Setlist\Application\Command\Setlist\Handler\Helper\SetlistHandlerHelper;
use Setlist\Application\Exception\InvalidSetlistException;
use Setlist\Application\Exception\SetlistNameNotUniqueException;
use Setlist\Application\Persistence\Setlist\ApplicationSetlistRepository;
use Setlist\Domain\Entity\Setlist\SetlistFactory;
use Setlist\Domain\Entity\Setlist\SetlistRepository;
use Setlist\Domain\Entity\Song\SongFactory;
use Setlist\Domain\Entity\Song\SongRepository;
use Setlist\Domain\Service\Setlist\SetlistNameValidator;

class CreateSetlistHandler
{
    private $applicationSetlistRepository;
    private $setlistRepository;
    private $songRepository;
    private $setlistFactory;
    private $songFactory;
    private $setlistHandlerHelper;

    public function __construct(
        ApplicationSetlistRepository $applicationSetlistRepository,
        SetlistRepository $setlistRepository,
        SongRepository $songRepository,
        SetlistFactory $setlistFactory,
        SongFactory $songFactory,
        SetlistHandlerHelper $setlistHandlerHelper
    ) {
        $this->applicationSetlistRepository = $applicationSetlistRepository;
        $this->setlistRepository = $setlistRepository;
        $this->setlistFactory = $setlistFactory;
        $this->songFactory = $songFactory;
        $this->songRepository = $songRepository;
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
        $setlistNames = $this->applicationSetlistRepository->getAllNames();
        $setlistNameValidator = SetlistNameValidator::create($setlistNames);
        if (!$setlistNameValidator->setlistNameIsUnique($command->name())) {
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
