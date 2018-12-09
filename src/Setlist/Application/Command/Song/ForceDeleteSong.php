<?php

namespace Setlist\Application\Command\Song;

use Setlist\Application\Command\BaseCommand;

class ForceDeleteSong extends BaseCommand
{
    public function uuid(): string
    {
        return $this->payload()['uuid'];
    }
}
