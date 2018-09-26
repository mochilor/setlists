<?php

namespace Setlist\Application\Command\Handler;

use Setlist\Application\Command\CreateSetlist;
use Setlist\Domain\Entity\Setlist\ActFactory;
use Setlist\Domain\Entity\Setlist\SetlistFactory;
use Setlist\Domain\Entity\Setlist\SetlistRepository;
use Setlist\Domain\Entity\Song\SongFactory;

class CreateSetlistHandler
{
    private $setlistRepository;
    private $setlistFactory;
    private $songFactory;
    private $actFactory;

    public function __construct(
        SetlistRepository $setlistRepository,
        SetlistFactory $setlistFactory,
        SongFactory $songFactory,
        ActFactory $actFactory
    ) {
        $this->setlistRepository = $setlistRepository;
        $this->setlistFactory = $setlistFactory;
        $this->songFactory = $songFactory;
        $this->actFactory = $actFactory;
    }

    public function __invoke(CreateSetlist $command)
    {
        $acts = $command->acts();
        $actsForSetlist = [];

        foreach ($acts as $act) {
            $songs = [];
            foreach ($act as $songData) {
                $songs[] = $this->songFactory->make($songData['uuid'], $songData['title']);
            }

            $actsForSetlist[] = $this->actFactory->make($songs);
        }

        $setlist = $this->setlistFactory->make($command->uuid(), $actsForSetlist, $command->name(), $command->dateTime());

        $this->setlistRepository->save($setlist);
    }
}
