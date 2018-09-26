<?php

namespace Setlist\Application\Command;

class DeleteSong extends BaseCommand
{
    public function uuid(): string
    {
        return $this->payload()['uuid'];
    }
}
