<?php

namespace Setlist\Domain\Entity\Song;

use Setlist\Domain\Exception\Song\InvalidSongTitleException;
use Setlist\Domain\Value\Uuid;

class Song
{
    private $id;
    private $title;

    const MIN_TITLE_LENGTH = 3;
    const MAX_TITLE_LENGTH = 30;

    public static function create(Uuid $id, string $title): self
    {
        $song = new self();
        $song->setId($id);
        $song->setTitle($title);

        // Event! NewSongCreated
        return $song;
    }

    private function setId(Uuid $id)
    {
        $this->id = $id;
    }

    private function setTitle(string $title)
    {
        $this->guardTitle($title);
        $this->title = $title;
    }

    private function guardTitle(string $title)
    {
        if (empty($title) || strlen($title) < self::MIN_TITLE_LENGTH || strlen($title) > self::MAX_TITLE_LENGTH) {
            throw new InvalidSongTitleException();
        }
    }

    public function id(): Uuid
    {
        return $this->id;
    }

    public function title(): string
    {
        return $this->title;
    }

    public function changeTitle(string $title)
    {
        if ($title != $this->title()) {
            $this->setTitle($title);
            // Event! SongChangedItsTitle
        }
    }

    public function isEqual(Song $song)
    {
        if ($this->title() != $song->title()) {
            return false;
        }

        return true;
    }
}
