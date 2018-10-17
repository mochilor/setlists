<?php

namespace Setlist\Application\Command\Setlist\Handler\Helper;

use Setlist\Application\Exception\InvalidSetlistException;
use Setlist\Domain\Entity\Setlist\ActFactory;
use Setlist\Domain\Entity\Song\Song;
use Setlist\Domain\Entity\Song\SongRepository;
use Setlist\Domain\Value\Uuid;

class SetlistHandlerHelper
{
    private $songRepository;
    private $actFactory;

    public function __construct(SongRepository $songRepository, ActFactory $actFactory)
    {
        $this->songRepository = $songRepository;
        $this->actFactory = $actFactory;
    }

    public function getActsForSetlist(array $acts): array
    {
        ksort($acts);
        $acts = array_values($acts);
        $actsForSetlist = [];

        foreach ($acts as $act) {
            $songs = [];
            foreach ($act as $key => $songUuid) {
                $song = $this->songRepository->get(Uuid::create($songUuid));
                if (!$song instanceof Song) {
                    throw new InvalidSetlistException('Invalid song provided');
                }

                $songs[$key] = $song;
            }

            $actsForSetlist[] = $this->actFactory->make($songs);
        }

        return $actsForSetlist;
    }
}