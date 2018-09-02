<?php

namespace Setlist\Infrastructure\Repository\PDO;

use Setlist\Domain\Entity\Song\Song;
use Setlist\Domain\Entity\Song\SongRepository as SongRepositoryInterface;
use Setlist\Domain\Value\Uuid;

class SongRepository implements SongRepositoryInterface
{
    public function nextIdentity(): Uuid
    {
        // TODO: Implement nextIdentity() method.
    }

    public function save(Song $song)
    {
        // TODO: Implement save() method.
    }

    public function get(Uuid $uuid)
    {
        // TODO: Implement get() method.
    }
}
