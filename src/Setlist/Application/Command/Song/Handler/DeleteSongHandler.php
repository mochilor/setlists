<?php

namespace Setlist\Application\Command\Song\Handler;

use Setlist\Application\Command\Song\DeleteSong;
use Setlist\Application\Exception\SongCanNotBeDeletedException;
use Setlist\Application\Exception\SongDoesNotExistException;
use Setlist\Domain\Entity\Song\Song;
use Setlist\Domain\Entity\Song\SongRepository;
use Setlist\Domain\Value\Uuid;
use Setlist\Application\Persistence\Setlist\SetlistRepository as ApplicationSetlistRepository;

class DeleteSongHandler
{
    private $songRepository;
    private $setlistRepository;

    public function __construct(SongRepository $songRepository, ApplicationSetlistRepository $setlistRepository) {
        $this->songRepository = $songRepository;
        $this->setlistRepository = $setlistRepository;
    }

    public function __invoke(DeleteSong $command)
    {
        $uuid = Uuid::create($command->uuid());
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
