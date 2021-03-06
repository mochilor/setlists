<?php

namespace Setlist\Infrastructure\DataTransformer;

use Setlist\Application\Persistence\Song\PersistedSong;
use Setlist\Application\DataTransformer\SongDataTransformer as SongDataTransformerInterface;

class SongDataTransformer implements SongDataTransformerInterface
{
    private $song;

    public function write(PersistedSong $song)
    {
        $this->song = $song;
    }

    public function read(): array
    {
        return [
            'id' => $this->song->id(),
            'title' => $this->song->title(),
            'is_visible' => $this->song->isVisible(),
            'creation_date' => $this->song->creationDate(),
            'update_date' => $this->song->updateDate(),
        ];
    }
}