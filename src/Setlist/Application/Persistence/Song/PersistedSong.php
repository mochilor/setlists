<?php

namespace Setlist\Application\Persistence\Song;

class PersistedSong
{
    private $id;
    private $title;
    private $creationDate;
    private $updateDate;

    public function __construct(string $id, string $title, string $creationDate, string $updateDate)
    {
        $this->id = $id;
        $this->title = $title;
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

    public function creationDate(): string
    {
        return $this->creationDate;
    }

    public function updateDate(): string
    {
        return $this->updateDate;
    }
}
