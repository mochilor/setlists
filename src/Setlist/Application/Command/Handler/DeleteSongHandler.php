<?php

namespace Setlist\Application\Command\Handler;

use Setlist\Application\Command\DeleteSong;
use Setlist\Domain\Entity\Song\Song;
use Setlist\Domain\Entity\Song\SongRepository;
use Setlist\Domain\Exception\Song\SongDoesNotExistException;
use Setlist\Domain\Value\Uuid;

class DeleteSongHandler
{
    private $songRepository;

    public function __construct(SongRepository $songRepository) {
        $this->songRepository = $songRepository;
    }

    public function __invoke(DeleteSong $command)
    {
        $uuid = Uuid::create($command->uuid());
        $song = $this->songRepository->get($uuid);

        if (!$song instanceof Song) {
            throw new SongDoesNotExistException('Song not found');
        }

        $song->delete();

        $this->songRepository->save($song);
    }
}
