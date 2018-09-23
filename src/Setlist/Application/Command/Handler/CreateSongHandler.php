<?php

namespace Setlist\Application\Command\Handler;

use Setlist\Application\Exception\SongTitleNotUniqueException;
use Setlist\Application\Command\CreateSong;
use Setlist\Application\Persistence\Song\ApplicationSongRepository;
use Setlist\Domain\Entity\Song\SongFactory;
use Setlist\Domain\Entity\Song\SongRepository;
use Setlist\Domain\Service\Song\SongTitleValidator;

class CreateSongHandler
{
    private $songRepository;
    private $applicationSongRepository;
    private $songFactory;

    public function __construct(
        SongRepository $songRepository,
        ApplicationSongRepository $applicationSongRepository,
        SongFactory $songFactory
    ) {
        $this->songRepository = $songRepository;
        $this->applicationSongRepository = $applicationSongRepository;
        $this->songFactory = $songFactory;
    }

    public function __invoke(CreateSong $command)
    {
        $songTitles = $this->applicationSongRepository->getAllTitles();
        $songTitleValidator = SongTitleValidator::create($songTitles);
        if (!$songTitleValidator->songTitleIsUnique($command->title())) {
            throw new SongTitleNotUniqueException();
        }

        $song = $this->songFactory->make(
            $this->songRepository->nextIdentity(),
            $command->title()
        );

        $this->songRepository->save($song);
    }
}
