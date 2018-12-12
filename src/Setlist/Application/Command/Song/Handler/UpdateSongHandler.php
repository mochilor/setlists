<?php

namespace Setlist\Application\Command\Song\Handler;

use Setlist\Application\Command\Song\UpdateSong;
use Setlist\Application\Exception\SongDoesNotExistException;
use Setlist\Application\Exception\SongTitleNotUniqueException;
use Setlist\Domain\Entity\Song\Song;
use Setlist\Domain\Entity\Song\SongRepository;
use Setlist\Domain\Entity\Song\SongTitleRepository;
use Setlist\Domain\Value\UuidGenerator;

class UpdateSongHandler
{
    private $songRepository;
    private $songTitleRepository;
    private $uuidGenerator;

    public function __construct(
        SongRepository $songRepository,
        SongTitleRepository $songTitleRepository,
        UuidGenerator $uuidGenerator
    ) {
        $this->songRepository = $songRepository;
        $this->songTitleRepository= $songTitleRepository;
        $this->uuidGenerator = $uuidGenerator;
    }

    public function __invoke(UpdateSong $command)
    {
        $uuid = $this->uuidGenerator->fromString($command->uuid());
        $song = $this->songRepository->get($uuid);

        $this->guard($command, $song);

        $song->changeTitle($command->title());
        $song->changeVisibility((bool)$command->isVisible());

        $this->songRepository->save($song);
    }

    private function guard(UpdateSong $command, $song)
    {
        if (!$song instanceof Song) {
            throw new SongDoesNotExistException('Song not found');
        }

        if (!$this->songTitleRepository->titleIsUnique($command->title(), $command->uuid())) {
            throw new SongTitleNotUniqueException('Song title already exists');
        }
    }
}
