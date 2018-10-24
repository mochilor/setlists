<?php

namespace Setlist\Application\Query\Song;

use Setlist\Application\Query\Query;

class GetSongs extends Query
{
    public function uuid(): string
    {
        return $this->payload()['uuid'];
    }

    public function start(): string
    {
        return $this->payload()['start'];
    }

    public function length(): string
    {
        return $this->payload()['length'];
    }
}