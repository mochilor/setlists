<?php

namespace Setlist\Application\Query\Song\Handler;

use Setlist\Application\DataTransformer\SongDataTransformer;
use Setlist\Application\Exception\SetlistDoesNotExistException;
use Setlist\Application\Persistence\Setlist\PersistedSetlist;
use Setlist\Application\Persistence\Setlist\PersistedSetlistRepository;
use Setlist\Application\Persistence\Song\PersistedSongRepository as ApplicationSongRepository;
use Setlist\Application\Query\Song\GetSongs;
use Setlist\Domain\Value\UuidGenerator;

class GetSongsHandler
{
    private $songDataTransformer;
    private $applicationSongRepository;
    private $uuidGenerator;
    private $persistedSetlistRepository;

    public function __construct(
        ApplicationSongRepository $applicationSongRepository,
        SongDataTransformer $songDataTransformer,
        UuidGenerator $uuidGenerator,
        PersistedSetlistRepository $persistedSetlistRepository
    ) {
        $this->applicationSongRepository = $applicationSongRepository;
        $this->songDataTransformer = $songDataTransformer;
        $this->uuidGenerator = $uuidGenerator;
        $this->persistedSetlistRepository = $persistedSetlistRepository;
    }

    public function __invoke(GetSongs $query)
    {
        $this->guard($query);

        $songs = $this->applicationSongRepository->getAllSongs(
            $query->start(),
            $query->length(),
            $query->title(),
            $query->notIn()
        );

        $transformedSongs = [];
        foreach ($songs as $song) {
            $this->songDataTransformer->write($song);
            $transformedSongs[] = $this->songDataTransformer->read();
        }

        return $transformedSongs;
    }

    private function guard(GetSongs $query)
    {
        if (empty($query->notIn())) {
            return '';
        }

        $setlistId = $this->uuidGenerator->fromString($query->notIn());
        $setlist = $this->persistedSetlistRepository->getOneSetlistById($setlistId->value());

        if (!$setlist instanceof PersistedSetlist) {
            throw new SetlistDoesNotExistException('Setlist not found');
        }
    }
}
