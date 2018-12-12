<?php

namespace Setlist\Application\Command\Song\Handler;

use Setlist\Application\Command\Song\CreateSong;
use Setlist\Application\Exception\SongTitleNotUniqueException;
use Setlist\Domain\Entity\Song\SongFactory;
use Setlist\Domain\Entity\Song\SongRepository;
use Setlist\Domain\Entity\Song\SongTitleRepository;


class CreateSongHandler
{
    private $songTitleRepository;
    private $songRepository;
    private $songFactory;

    public function __construct(
        SongTitleRepository $songTitleRepository,
        SongRepository $songRepository,
        SongFactory $songFactory
    ) {
        $this->songTitleRepository = $songTitleRepository;
        $this->songRepository = $songRepository;
        $this->songFactory = $songFactory;
    }

    public function __invoke(CreateSong $command)
    {
        if (!$this->songTitleRepository->titleIsAvailable($command->title())) {
            throw new SongTitleNotUniqueException();
        }

        $song = $this->songFactory->make(
            $command->uuid(),
            $command->title()
        );

        $this->songRepository->save($song);
    }
}
