<?php

namespace Setlist\Application\Query\Song\Handler;

use Setlist\Application\DataTransformer\SongDataTransformer;
use Setlist\Application\Query\Song\GetSongsByTitle;
use Setlist\Application\Persistence\Song\PersistedSongRepository as ApplicationSongRepository;

class GetSongsByTitleHandler
{
    private $songDataTransformer;
    private $applicationSongRepository;

    public function __construct(ApplicationSongRepository $applicationSongRepository, SongDataTransformer $songDataTransformer)
    {
        $this->applicationSongRepository = $applicationSongRepository;
        $this->songDataTransformer = $songDataTransformer;
    }

    public function __invoke(GetSongsByTitle $query)
    {
        $songs = $this->applicationSongRepository->getSongsByTitle($query->title());

        $transformedSongs = [];
        foreach ($songs as $song) {
            $this->songDataTransformer->write($song);
            $transformedSongs[] = $this->songDataTransformer->read();
        }

        return $transformedSongs;
    }
}
