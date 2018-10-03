<?php

namespace Setlist\Domain\Entity\Song;

use DateTimeImmutable;
use Setlist\Domain\Entity\EventsTrigger;
use Setlist\Domain\Entity\Song\Event\SongChangedItsTitle;
use Setlist\Domain\Entity\Song\Event\SongWasDeleted;
use Setlist\Domain\Exception\Song\InvalidSongTitleException;
use Setlist\Domain\Value\Uuid;

class Song
{
    private $id;
    private $title;
    private $eventsTrigger;
    private $dateTime;

    const MIN_TITLE_LENGTH = 3;
    const MAX_TITLE_LENGTH = 30;
    const DATE_TIME_FORMAT = 'Y-m-d H:i:s';

    public static function create(Uuid $id, string $title, DateTimeImmutable $dateTime, EventsTrigger $eventsTrigger): self
    {
        $song = new self();
        $song->eventsTrigger = $eventsTrigger;
        $song->setId($id);
        $song->setTitle($title);
        $song->setDatetime($dateTime);

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

    private function setDatetime(\DateTimeImmutable $dateTime)
    {
        $this->dateTime = $dateTime;
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

    public function dateTime(): DateTimeImmutable
    {
        return $this->dateTime;
    }

    public function formattedDateTime(): string
    {
        return $this->dateTime->format(self::DATE_TIME_FORMAT);
    }

    public function changeTitle(string $title)
    {
        if ($title != $this->title()) {
            $this->setTitle($title);
            $this->eventsTrigger->trigger(SongChangedItsTitle::create($this->id(), $title));
        }
    }

    public function isEqual(Song $song)
    {
        if ($this->title() != $song->title()) {
            return false;
        }

        return true;
    }

    public function delete()
    {
        $this->eventsTrigger->trigger(SongWasDeleted::create($this->id()));
    }

    public function events()
    {
        return $this->eventsTrigger->events();
    }
}
