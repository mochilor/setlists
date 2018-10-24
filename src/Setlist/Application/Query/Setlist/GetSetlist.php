<?php

namespace Setlist\Application\Query\Setlist;

use Setlist\Application\Query\Query;

class GetSetlist extends Query
{
    public function uuid(): string
    {
        return $this->payload()['uuid'];
    }
}
