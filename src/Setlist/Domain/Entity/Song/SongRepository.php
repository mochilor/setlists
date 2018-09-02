<?php

namespace Setlist\Domain\Entity\Song;

use Setlist\Domain\Value\Uuid;

interface SongRepository
{
    public function nextIdentity(): Uuid;
    public function save(Song $song);
    public function get(Uuid $uuid);
}
