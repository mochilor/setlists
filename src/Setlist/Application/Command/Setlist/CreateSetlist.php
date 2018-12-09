<?php

namespace Setlist\Application\Command\Setlist;

use Setlist\Application\Command\BaseCommand;

class CreateSetlist extends BaseCommand
{
    public function name(): string
    {
        return $this->payload()['name'];
    }

    public function description(): string
    {
        return $this->payload()['description'];
    }

    public function acts(): array
    {
        return $this->payload()['acts'];
    }

    public function date(): string
    {
        return $this->payload()['date'];
    }

    public function successCode(): int
    {
        return 201;
    }
}
