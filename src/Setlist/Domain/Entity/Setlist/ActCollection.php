<?php

namespace Setlist\Domain\Entity\Setlist;

use ArrayObject;

class ActCollection extends ArrayObject
{
    public static function create(Act ...$acts): self
    {
        return new self($acts, self::ARRAY_AS_PROPS);
    }
}
