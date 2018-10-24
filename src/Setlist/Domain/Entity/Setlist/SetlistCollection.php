<?php

namespace Setlist\Domain\Entity\Setlist;

use ArrayObject;

class SetlistCollection extends ArrayObject
{
    public static function create(Setlist ...$setlists): self
    {
        return new self($setlists, self::ARRAY_AS_PROPS);
    }
}
