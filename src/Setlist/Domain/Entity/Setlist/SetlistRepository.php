<?php

namespace Setlist\Domain\Entity\Setlist;

use Setlist\Domain\Value\Uuid;

interface SetlistRepository
{
    public function save(Setlist $setlist);
    public function get(Uuid $uuid): ?Setlist;
}
