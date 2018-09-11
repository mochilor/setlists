<?php

namespace Setlist\Domain\Entity\Setlist;

use DateTime;
use Setlist\Domain\Exception\Song\InvalidSetlistNameException;
use Setlist\Domain\Value\Uuid;

class Setlist
{
    private $id;
    private $songCollection;
    private $name;
    private $date;

    const MIN_NAME_LENGTH = 3;
    const MAX_NAME_LENGTH = 30;

    public static function create(Uuid $id, SongCollection $songCollection, string $name, DateTime $date): self
    {
        $setlist = new self();

        $setlist->setId($id);
        $setlist->setSongCollection($songCollection);
        $setlist->setName($name);
        $setlist->setDatetime($date);

        return $setlist;
    }

    private function setId(Uuid $id)
    {
        $this->id = $id;
    }

    private function setSongCollection(SongCollection $songCollection)
    {
        $this->songCollection = $songCollection;
    }

    private function setName(string $name)
    {
        $this->guardName($name);
        $this->name = $name;
    }

    private function guardName(string $name)
    {
        if (empty($name) || strlen($name) < self::MIN_NAME_LENGTH || strlen($name) > self::MAX_NAME_LENGTH) {
            throw new InvalidSetlistNameException();
        }
    }

    private function setDatetime(DateTime $date)
    {
        $this->date = $date;
    }

    public function songCollection(): SongCollection
    {
        return $this->songCollection;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function fullName(): string
    {
        return sprintf('%s - %s', $this->formattedDate(), $this->name());
    }

    public function date(): DateTime
    {
        return $this->date;
    }

    public function formattedDate(): string
    {
        return $this->date->format('Y-m-d');
    }

    public function changeName(string $name)
    {
        if ($name != $this->name()) {
            $this->setName($name);
            // Event
        }
    }

    public function changeDate(DateTime $date)
    {
        if ($date != $this->date()) {
            $this->setDatetime($date);
            // Event
        }
    }

    public function changeSongCollection(SongCollection $songCollection)
    {
        foreach ($songCollection as $key => $song) {
            if (!isset($this->songCollection()[$key]) || !$song->isEqual($this->songCollection()[$key])) {
                $this->setSongCollection($songCollection);
                // Event
                break;
            }
        }
    }
}
