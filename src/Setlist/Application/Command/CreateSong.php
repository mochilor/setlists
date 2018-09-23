<?php

namespace Setlist\Application\Command;

class CreateSong
{
    private $title;

    public static function create(string $title)
    {
        $command = new self();
        $command->title = $title;

        return $command;
    }

    public function title(): string
    {
        return $this->title;
    }
}
