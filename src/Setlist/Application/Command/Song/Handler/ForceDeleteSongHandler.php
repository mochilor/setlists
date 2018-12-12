<?php

namespace Setlist\Application\Command\Song\Handler;

use Setlist\Application\Command\Song\ForceDeleteSong;
use Setlist\Application\Exception\SongDoesNotExistException;
use Setlist\Domain\Entity\Song\Song;
use Setlist\Domain\Entity\Song\SongRepository;
use Setlist\Domain\Value\UuidGenerator;

class ForceDeleteSongHandler
{
    private $songRepository;
    private $uuidGenerator;

    public function __construct(SongRepository $songRepository, UuidGenerator $uuidGenerator) {
        $this->songRepository = $songRepository;
        $this->uuidGenerator = $uuidGenerator;
    }

    public function __invoke(ForceDeleteSong $command)
    {
        $uuid = $this->uuidGenerator->fromString($command->uuid());
        $song = $this->songRepository->get($uuid);

        if (!$song instanceof Song) {
            throw new SongDoesNotExistException('Song not found');
        }

        $song->delete();

        $this->songRepository->save($song);
    }
}
