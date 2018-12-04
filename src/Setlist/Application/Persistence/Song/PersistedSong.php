<?php

namespace Setlist\Application\Persistence\Song;

class PersistedSong
{
    private $id;
    private $title;
    private $isVisible;
    private $creationDate;
    private $updateDate;

    public function __construct(string $id, string $title, int $isVisible, string $creationDate, string $updateDate)
    {
        $this->id = $id;
        $this->title = $title;
        $this->isVisible = $isVisible;
        $this->creationDate = $creationDate;
        $this->updateDate = $updateDate;
    }

    public function id(): string
    {
        return $this->id;
    }

    public function title(): string
    {
        return $this->title;
    }

    public function isVisible(): int
    {
        return $this->isVisible;
    }

    public function creationDate(): string
    {
        return $this->creationDate;
    }

    public function updateDate(): string
    {
        return $this->updateDate;
    }
}
