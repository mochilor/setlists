<?php

namespace Setlist\Domain\Entity\Song;

use DateTimeImmutable;
use Setlist\Domain\Entity\EventsTrigger;
use Setlist\Domain\Entity\Song\Event\SongChangedItsTitle;
use Setlist\Domain\Entity\Song\Event\SongWasCreated;
use Setlist\Domain\Entity\Song\Event\SongWasDeleted;
use Setlist\Domain\Entity\Song\Event\SongWasHidden;
use Setlist\Domain\Entity\Song\Event\SongWasUnhidden;
use Setlist\Domain\Exception\Song\InvalidSongTitleException;
use Setlist\Domain\Value\Uuid;

class Song
{
    private $id;
    private $title;
    private $isVisible;
    private $creationDate;
    private $updateDate;
    private $eventsTrigger;

    const MIN_TITLE_LENGTH = 3;
    const MAX_TITLE_LENGTH = 30;
    const CREATION_DATE_FORMAT = 'Y-m-d H:i:s';
    const UPDATE_DATE_FORMAT = 'Y-m-d H:i:s';

    public static function create(
        Uuid $id,
        string $title,
        DateTimeImmutable $creationDate,
        DateTimeImmutable $updateDate,
        EventsTrigger $eventsTrigger
    ): self
    {
        $song = self::restore($id, $title, true, $creationDate, $updateDate, $eventsTrigger);

        $song->eventsTrigger->trigger(
            SongWasCreated::create(
                $song->id(),
                $song->title(),
                $song->formattedCreationDate()
            )
        );

        return $song;
    }

    public static function restore(
        Uuid $id,
        string $title,
        bool $isVisible,
        DateTimeImmutable $creationDate,
        DateTimeImmutable $updateDate,
        EventsTrigger $eventsTrigger
    )
    {
        $song = new self();
        $song->eventsTrigger = $eventsTrigger;
        $song->setId($id);
        $song->setTitle($title);
        $song->setVisibility($isVisible);
        $song->setCreationDate($creationDate);
        $song->setUpdateDate($updateDate);

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

    private function setCreationDate(DateTimeImmutable $dateTime)
    {
        $this->creationDate = $dateTime;
    }

    private function setUpdateDate(DateTimeImmutable $updateDate)
    {
        $this->updateDate = $updateDate;
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

    public function isVisible()
    {
        return $this->isVisible;
    }

    public function creationDate(): DateTimeImmutable
    {
        return $this->creationDate;
    }

    public function updateDate(): DateTimeImmutable
    {
        return $this->updateDate;
    }

    public function formattedCreationDate(): string
    {
        return $this->creationDate->format(self::CREATION_DATE_FORMAT);
    }

    public function formattedUpdateDate(): string
    {
        return $this->updateDate->format(self::UPDATE_DATE_FORMAT);
    }

    public function changeTitle(string $title)
    {
        if ($title != $this->title()) {
            $this->setTitle($title);
            $newUpdateDate = new DateTimeImmutable();
            $this->setUpdateDate($newUpdateDate);

            $this->eventsTrigger->trigger(
                SongChangedItsTitle::create(
                    $this->id(),
                    $title,
                    $newUpdateDate->format(self::UPDATE_DATE_FORMAT)
                )
            );
        }
    }

    public function setVisibility(bool $isVisible)
    {
        $this->isVisible = $isVisible;
    }

    public function changeVisibility(bool $isVisible)
    {
        if ($isVisible != $this->isVisible()) {
            $isVisible ? $this->unhide() : $this->hide();
        }
    }

    public function hide()
    {
        if ($this->isVisible()) {
            $this->setVisibility(false);
            $newUpdateDate = new DateTimeImmutable();
            $this->setUpdateDate($newUpdateDate);

            $this->eventsTrigger->trigger(
                SongWasHidden::create(
                    $this->id(),
                    $newUpdateDate->format(self::UPDATE_DATE_FORMAT)
                )
            );
        }
    }

    public function unhide()
    {
        if (!$this->isVisible()) {
            $this->setVisibility(true);
            $newUpdateDate = new DateTimeImmutable();
            $this->setUpdateDate($newUpdateDate);

            $this->eventsTrigger->trigger(
                SongWasUnhidden::create(
                    $this->id(),
                    $newUpdateDate->format(self::UPDATE_DATE_FORMAT)
                )
            );
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

    public function events(): array
    {
        return $this->eventsTrigger->events();
    }
}
