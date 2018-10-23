<?php

namespace Setlist\Application\DataTransformer;

use Setlist\Domain\Entity\Song\Song;

class SongDataTransformer
{
    private $song;

    public function write(Song $song)
    {
        $this->song = $song;
    }

    public function read(): array
    {
        return [
            'id' => (string) $this->song->id(),
            'title' => $this->song->title(),
            'creation_date' => $this->song->formattedCreationDate(),
            'update_date' => $this->song->formattedUpdateDate(),
        ];
    }
}