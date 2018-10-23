<?php

namespace Setlist\Application\Query\Song;

use Setlist\Application\Query\Query;

class GetSong extends Query
{
    public function uuid(): string
    {
        return $this->payload()['uuid'];
    }
}
