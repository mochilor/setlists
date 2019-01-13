<?php

namespace Setlist\Application\Query\Song\Handler;

use Setlist\Application\DataTransformer\SetlistDataTransformer;
use Setlist\Application\Exception\SongDoesNotExistException;
use Setlist\Application\Persistence\Setlist\PersistedSetlistRepository;
use Setlist\Application\Persistence\Song\PersistedSongRepository;
use Setlist\Application\Persistence\Song\PersistedSong;
use Setlist\Application\Query\Song\GetSongStats;
use Setlist\Domain\Value\UuidGenerator;

class GetSongStatsHandler
{
    private $uuidGenerator;
    private $persistedSongRepository;
    private $persistedSetlistRepository;
    private $setlistDataTransformer;

    public function __construct(
        UuidGenerator $uuidGenerator,
        PersistedSongRepository $persistedSongRepository,
        PersistedSetlistRepository $persistedSetlistRepository,
        SetlistDataTransformer $setlistDataTransformer
    ) {
        $this->uuidGenerator = $uuidGenerator;
        $this->persistedSongRepository = $persistedSongRepository;
        $this->persistedSetlistRepository = $persistedSetlistRepository;
        $this->setlistDataTransformer = $setlistDataTransformer;
    }

    public function __invoke(GetSongStats $query)
    {
        $uuid = $this->uuidGenerator->fromString($query->uuid());
        $song = $this->persistedSongRepository->getOneSongById($uuid->value());

        if (!$song instanceof PersistedSong) {
            throw new SongDoesNotExistException('Song not found');
        }

        $setlists = $this->persistedSetlistRepository->getSetlistsInfoBySongId($uuid->value());

        $transformedSetlists = [];
        foreach ($setlists as $setlist) {
            $this->setlistDataTransformer->write($setlist);
            $transformedSetlists[] = $this->setlistDataTransformer->read();
        }

        return $transformedSetlists;
    }
}
