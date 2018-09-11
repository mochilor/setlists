<?php

namespace Setlist\Domain\Entity\Setlist;

use ArrayObject;
use Setlist\Domain\Entity\Song\Song;

class SongCollection extends ArrayObject
{
    public static function create(Song ...$songs): self
    {
        return new self($songs, self::ARRAY_AS_PROPS);
    }
}
