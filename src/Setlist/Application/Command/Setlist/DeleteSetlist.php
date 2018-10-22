<?php

namespace Setlist\Application\Command\Setlist;

use Setlist\Application\Command\BaseCommand;

class DeleteSetlist extends BaseCommand
{
    public function uuid(): string
    {
        return $this->payload()['uuid'];
    }
}
