<?php

namespace Setlist\Application\Persistence\Setlist;

use ArrayObject;

class PersistedSetlistCollection extends ArrayObject
{
    public static function create(PersistedSetlist ...$setlists): self
    {
        return new self($setlists, self::ARRAY_AS_PROPS);
    }
}
