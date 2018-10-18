<?php

namespace Setlist\Application\Command\Song\Handler;

use Setlist\Application\Command\Song\UpdateSong;
use Setlist\Application\Exception\SongDoesNotExistException;
use Setlist\Application\Exception\SongTitleNotUniqueException;
use Setlist\Application\Persistence\Song\SongRepository as ApplicationSongRespository;
use Setlist\Domain\Entity\Song\Song;
use Setlist\Domain\Entity\Song\SongRepository;
use Setlist\Domain\Value\Uuid;

class UpdateSongHandler
{
    private $songRepository;
    private $applicationSongRepository;

    public function __construct(SongRepository $songRepository, ApplicationSongRespository $applicationSongRepository)
    {
        $this->songRepository = $songRepository;
        $this->applicationSongRepository = $applicationSongRepository;
    }

    public function __invoke(UpdateSong $command)
    {
        $uuid = Uuid::create($command->uuid());
        $song = $this->songRepository->get($uuid);

        $this->guard($command, $song);

        $song->changeTitle($command->title());

        $this->songRepository->save($song);
    }

    private function guard(UpdateSong $command, $song)
    {
        if (!$song instanceof Song) {
            throw new SongDoesNotExistException('Song not found');
        }

        $otherTitles = $this->applicationSongRepository->getOtherTitles($command->uuid());
        if (in_array($command->title(), $otherTitles)) {
            throw new SongTitleNotUniqueException('Song title already exists');
        }
    }
}
