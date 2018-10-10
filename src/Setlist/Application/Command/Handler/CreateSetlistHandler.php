<?php

namespace Setlist\Application\Command\Handler;

use Setlist\Application\Command\CreateSetlist;
use Setlist\Application\Exception\SetlistNameNotUniqueException;
use Setlist\Application\Persistence\Setlist\ApplicationSetlistRepository;
use Setlist\Domain\Entity\Setlist\ActFactory;
use Setlist\Domain\Entity\Setlist\SetlistFactory;
use Setlist\Domain\Entity\Setlist\SetlistRepository;
use Setlist\Domain\Entity\Song\Song;
use Setlist\Domain\Entity\Song\SongFactory;
use Setlist\Domain\Entity\Song\SongRepository;
use Setlist\Domain\Service\Setlist\SetlistNameValidator;
use Setlist\Domain\Value\Uuid;

class CreateSetlistHandler
{
    private $applicationSetlistRepository;
    private $setlistRepository;
    private $songRepository;
    private $setlistFactory;
    private $songFactory;
    private $actFactory;

    public function __construct(
        ApplicationSetlistRepository $applicationSetlistRepository,
        SetlistRepository $setlistRepository,
        SongRepository $songRepository,
        SetlistFactory $setlistFactory,
        SongFactory $songFactory,
        ActFactory $actFactory
    ) {
        $this->applicationSetlistRepository = $applicationSetlistRepository;
        $this->setlistRepository = $setlistRepository;
        $this->setlistFactory = $setlistFactory;
        $this->songFactory = $songFactory;
        $this->actFactory = $actFactory;
        $this->songRepository = $songRepository;
    }

    public function __invoke(CreateSetlist $command)
    {
        $setlistNames = $this->applicationSetlistRepository->getAllNames();
        $setlistNameValidator = SetlistNameValidator::create($setlistNames);
        if (!$setlistNameValidator->setlistNameIsUnique($command->name())) {
            throw new SetlistNameNotUniqueException();
        }

        $acts = $command->acts();
        $actsForSetlist = [];

        foreach ($acts as $act) {
            $songs = [];
            foreach ($act as $songUuid) {
                $song = $this->songRepository->get(Uuid::create($songUuid));
                if ($song instanceof Song) {
                    $songs[] = $song;
                }
            }

            $actsForSetlist[] = $this->actFactory->make($songs);
        }

        $setlist = $this->setlistFactory->make(
            $this->setlistRepository->nextIdentity(),
            $actsForSetlist,
            $command->name(),
            $command->date()
        );

        $this->setlistRepository->save($setlist);
    }
}
