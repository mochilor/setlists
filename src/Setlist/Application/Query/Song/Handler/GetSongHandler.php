<?php

namespace Setlist\Application\Query\Song\Handler;

use Setlist\Application\DataTransformer\SongDataTransformer;
use Setlist\Application\Exception\SongDoesNotExistException;
use Setlist\Application\Query\Song\GetSong;
use Setlist\Domain\Entity\Song\Song;
use Setlist\Domain\Entity\Song\SongRepository;
use Setlist\Domain\Value\Uuid;

class GetSongHandler
{
    private $songRepository;
    private $songDataTransformer;

    public function __construct(SongRepository $songRepository, SongDataTransformer $songDataTransformer)
    {
        $this->songRepository = $songRepository;
        $this->songDataTransformer = $songDataTransformer;
    }

    public function __invoke(GetSong $query)
    {
        $uuid = Uuid::create($query->uuid());
        $song = $this->songRepository->get($uuid);

        if (!$song instanceof Song) {
            throw new SongDoesNotExistException('Song not found');
        }

        $this->songDataTransformer->write($song);

        return $this->songDataTransformer->read();
    }
}
