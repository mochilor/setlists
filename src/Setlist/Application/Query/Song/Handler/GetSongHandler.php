<?php

namespace Setlist\Application\Query\Song\Handler;

use Setlist\Application\DataTransformer\SongDataTransformer;
use Setlist\Application\Exception\SongDoesNotExistException;
use Setlist\Application\Persistence\Song\PersistedSong;
use Setlist\Application\Persistence\Song\PersistedSongRepository as ApplicationSongRepository;
use Setlist\Application\Query\Song\GetSong;
use Setlist\Domain\Value\UuidGenerator;

class GetSongHandler
{
    private $songRepository;
    private $songDataTransformer;
    private $uuidGenerator;

    public function __construct(
        ApplicationSongRepository $songRepository,
        SongDataTransformer $songDataTransformer,
        UuidGenerator $uuidGenerator
    ) {
        $this->songRepository = $songRepository;
        $this->songDataTransformer = $songDataTransformer;
        $this->uuidGenerator = $uuidGenerator;
    }

    public function __invoke(GetSong $query)
    {
        $uuid = $this->uuidGenerator->fromString($query->uuid());
        $song = $this->songRepository->getOneSongById($uuid->value());

        if (!$song instanceof PersistedSong) {
            throw new SongDoesNotExistException('Song not found');
        }

        $this->songDataTransformer->write($song);

        return $this->songDataTransformer->read();
    }
}
