<?php

namespace Setlist\Application\Command\Song;

use Setlist\Application\Command\BaseCommand;

class CreateSong extends BaseCommand
{
    public function title(): string
    {
        return $this->payload()['title'];
    }

    public function successCode(): int
    {
        return 201;
    }
}
