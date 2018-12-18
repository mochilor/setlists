<?php

namespace Setlist\Application\Command\Song\Handler;

use Setlist\Application\Command\Song\CreateSong;
use Setlist\Application\Exception\SongIdNotUniqueException;
use Setlist\Application\Exception\SongTitleNotUniqueException;
use Setlist\Domain\Entity\Song\SongFactory;
use Setlist\Domain\Entity\Song\SongRepository;
use Setlist\Domain\Entity\Song\SongAvailabilityRepository;


class CreateSongHandler
{
    private $songAvailabilityRepository;
    private $songRepository;
    private $songFactory;

    public function __construct(
        SongAvailabilityRepository $songAvailabilityRepository,
        SongRepository $songRepository,
        SongFactory $songFactory
    ) {
        $this->songAvailabilityRepository = $songAvailabilityRepository;
        $this->songRepository = $songRepository;
        $this->songFactory = $songFactory;
    }

    public function __invoke(CreateSong $command)
    {
        if (!$this->songAvailabilityRepository->idIsAvailable($command->uuid())) {
            throw new SongIdNotUniqueException();
        }

        if (!$this->songAvailabilityRepository->titleIsAvailable($command->title())) {
            throw new SongTitleNotUniqueException();
        }

        $song = $this->songFactory->make(
            $command->uuid(),
            $command->title()
        );

        $this->songRepository->save($song);
    }
}
