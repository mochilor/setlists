<?php

namespace Setlist\Application\Command\Song\Handler;

use Setlist\Application\Command\Song\ForceDeleteSong;
use Setlist\Application\Exception\SongDoesNotExistException;
use Setlist\Domain\Entity\Song\Song;
use Setlist\Domain\Entity\Song\SongRepository;
use Setlist\Domain\Value\Uuid;

class ForceDeleteSongHandler
{
    private $songRepository;

    public function __construct(SongRepository $songRepository) {
        $this->songRepository = $songRepository;
    }

    public function __invoke(ForceDeleteSong $command)
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
