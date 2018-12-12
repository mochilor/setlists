<?php

namespace Setlist\Application\Command\Song\Handler;

use Setlist\Application\Command\Song\DeleteSong;
use Setlist\Application\Exception\SongCanNotBeDeletedException;
use Setlist\Application\Exception\SongDoesNotExistException;
use Setlist\Domain\Entity\Song\Song;
use Setlist\Domain\Entity\Song\SongRepository;
use Setlist\Application\Persistence\Setlist\SetlistRepository as ApplicationSetlistRepository;
use Setlist\Domain\Value\UuidGenerator;

class DeleteSongHandler
{
    private $songRepository;
    private $setlistRepository;
    private $uuidGenerator;

    public function __construct(
        SongRepository $songRepository,
        ApplicationSetlistRepository $setlistRepository,
        UuidGenerator $uuidGenerator
    ) {
        $this->songRepository = $songRepository;
        $this->setlistRepository = $setlistRepository;
        $this->uuidGenerator = $uuidGenerator;
    }

    public function __invoke(DeleteSong $command)
    {
        $uuid = $this->uuidGenerator->fromString($command->uuid());
        $song = $this->songRepository->get($uuid);

        if (!$song instanceof Song) {
            throw new SongDoesNotExistException('Song not found');
        }

        $setlistsCount = $this->setlistRepository->getSelistsCountBySongId($command->uuid());
        if ($setlistsCount > 0) {
            throw new SongCanNotBeDeletedException(
                sprintf('The song is present in %d setlist%s', $setlistsCount, $setlistsCount == 1 ? '' : 's')
            );
        }

        $song->delete();

        $this->songRepository->save($song);
    }
}
