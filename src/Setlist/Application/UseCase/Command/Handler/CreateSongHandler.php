<?php

namespace Setlist\Application\UseCase\Command\Handler;

use Setlist\Application\Exception\SongTitleNotUniqueException;
use Setlist\Application\UseCase\Command\CreateSong;
use Setlist\Domain\Entity\Song\Song;
use Setlist\Domain\Entity\Song\SongRepository;
use Setlist\Domain\Validator\Song\SongTitleValidator;
use Setlist\Infrastructure\Persistence\PDO\ApplicationSongRepository;

class CreateSongHandler
{
    private $songRepository;
    private $applicationSongRepository;

    public function __construct(SongRepository $songRepository, ApplicationSongRepository $applicationSongRepository)
    {
        $this->songRepository = $songRepository;
        $this->applicationSongRepository = $applicationSongRepository;
    }

    public function __invoke(CreateSong $command)
    {
        $songTitles = $this->applicationSongRepository->getAllTitles();
        $songTitleValidator = SongTitleValidator::create($songTitles);
        if (!$songTitleValidator->songTitleIsUnique($command->name())) {
            throw new SongTitleNotUniqueException();
        }

        $song = Song::create(
            $this->songRepository->nextIdentity(),
            $command->name()
        );

        $this->songRepository->save($song);
    }
}
