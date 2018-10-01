<?php

namespace Setlist\Domain\Entity\Song;

use Setlist\Domain\Value\Uuid;

class SongFactory
{
    public function make(Uuid $uuid, string $title): Song
    {
        return Song::create($uuid, $title);
    }

    public function restore(Uuid $uuid, string $title): Song
    {
        return Song::restore($uuid, $title);
    }
}
