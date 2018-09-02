<?php

namespace Setlist\Application\UseCase\Command;

class CreateSong
{
    private $name;

    private function __construct(string $name)
    {
        $this->name = $name;
    }

    public static function create(string $name)
    {
        return new self($name);
    }

    public function name(): string
    {
        return $this->name;
    }
}
