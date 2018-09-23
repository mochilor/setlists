<?php

namespace Setlist\Domain\Service\Song;

class SongTitleValidator
{
    private $titles;

    private function __construct(array $titles)
    {
        $this->titles = $titles;
    }

    public static function create(array $titles): self
    {
        return new self($titles);
    }

    public function songTitleIsUnique(string $title): bool
    {
        return !in_array($title, $this->titles);
    }
}
