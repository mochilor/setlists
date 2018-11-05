<?php

namespace Setlist\Infrastructure\DataTransformer;

use Setlist\Domain\Entity\Song\Song;
use Setlist\Application\DataTransformer\SongDataTransformer as SongDataTransformerInterface;

class SongDataTransformer implements SongDataTransformerInterface
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