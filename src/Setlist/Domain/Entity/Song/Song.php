<?php

namespace Setlist\Domain\Entity\Song;

use Setlist\Domain\Exception\Song\InvalidNameException;
use Setlist\Domain\Value\Uuid;

class Song
{
    private $id;
    private $name;

    const MIN_NAME_LENGTH = 3;
    const MAX_NAME_LENGTH = 30;

    public static function create(Uuid $id, string $name)
    {
        $song = new self();
        $song->setId($id);
        $song->setName($name);

        // Event! NewSongCreated
        return $song;
    }

    private function setId(Uuid $id)
    {
        $this->id = $id;
    }

    private function setName(string $name)
    {
        $this->guardName($name);
        $this->name = $name;
    }

    private function guardName(string $name)
    {
        if (empty($name) || strlen($name) < self::MIN_NAME_LENGTH || strlen($name) > self::MAX_NAME_LENGTH) {
            throw new InvalidNameException();
        }
    }

    public function id(): Uuid
    {
        return $this->id;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function changeName(string $name)
    {
        $this->guardName($name);
        $this->name = $name;
        // Event! SongChangedItsName
    }
}
