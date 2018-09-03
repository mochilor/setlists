<?php

namespace Setlist\Application\Command;

class CreateSong
{
    private $title;

    private function __construct(string $title)
    {
        $this->title = $title;
    }

    public static function create(string $title)
    {
        return new self($title);
    }

    public function title(): string
    {
        return $this->title;
    }
}
