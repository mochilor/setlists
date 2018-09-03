<?php

namespace Setlist\Domain\Entity\Song;

use Setlist\Domain\Value\Uuid;

class SongFactory
{
    public function make(Uuid $uuid, string $title)
    {
        return Song::create($uuid, $title);
    }
}
