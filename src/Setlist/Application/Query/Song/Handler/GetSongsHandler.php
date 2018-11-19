<?php

namespace Setlist\Application\Query\Song\Handler;

use Setlist\Application\DataTransformer\SongDataTransformer;
use Setlist\Application\Persistence\Song\SongRepository as ApplicationSongRepository;
use Setlist\Application\Query\Song\GetSongs;

class GetSongsHandler
{
    private $songDataTransformer;
    private $applicationSongRepository;

    public function __construct(ApplicationSongRepository $applicationSongRepository, SongDataTransformer $songDataTransformer)
    {
        $this->applicationSongRepository = $applicationSongRepository;
        $this->songDataTransformer = $songDataTransformer;
    }

    public function __invoke(GetSongs $query)
    {
        $songs = $this->applicationSongRepository->getAllSongs($query->start(), $query->length());

        $transformedSongs = [];
        foreach ($songs as $song) {
            $this->songDataTransformer->write($song);
            $transformedSongs[] = $this->songDataTransformer->read();
        }

        return $transformedSongs;
    }
}
