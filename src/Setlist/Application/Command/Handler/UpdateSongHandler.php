<?php

namespace Setlist\Application\Command\Handler;

use Setlist\Application\Command\UpdateSong;
use Setlist\Domain\Entity\Song\Song;
use Setlist\Domain\Entity\Song\SongRepository;
use Setlist\Domain\Exception\Song\SongDoesNotExistException;
use Setlist\Domain\Value\Uuid;

class UpdateSongHandler
{
    private $songRepository;

    public function __construct(SongRepository $songRepository) {
        $this->songRepository = $songRepository;
    }

    public function __invoke(UpdateSong $command)
    {
        $uuid = Uuid::create($command->uuid());
        $song = $this->songRepository->get($uuid);

        if (!$song instanceof Song) {
            throw new SongDoesNotExistException('Song not found');
        }

        $song->changeTitle($command->title());

        $this->songRepository->save($song);
    }
}
