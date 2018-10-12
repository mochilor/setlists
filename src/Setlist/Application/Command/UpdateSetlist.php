<?php

namespace Setlist\Application\Command;

class UpdateSetlist extends BaseCommand
{
    public function uuid(): string
    {
        return $this->payload()['uuid'];
    }

    public function name(): string
    {
        return $this->payload()['name'];
    }

    public function acts(): array
    {
        return $this->payload()['acts'];
    }

    public function date(): string
    {
        return $this->payload()['date'];
    }
}
