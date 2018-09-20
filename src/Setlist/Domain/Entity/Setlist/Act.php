<?php

namespace Setlist\Domain\Entity\Setlist;

use Setlist\Domain\Exception\Setlist\InvalidSongCollectionException;

// VO of Setlist?
class Act
{
    private $songCollection;

    public static function create(SongCollection $songCollection): self
    {
        $act = new self();
        $act->setSongCollection($songCollection);

        return $act;
    }

    private function setSongCollection(SongCollection $songCollection)
    {
        $this->guardSongCollection($songCollection);
        $this->songCollection = $songCollection;
    }

    private function guardSongCollection(SongCollection $songCollection)
    {
        if ($songCollection->count() == 0) {
            throw new InvalidSongcollectionException();
        }
    }

    public function songCollection(): SongCollection
    {
        return $this->songCollection;
    }

    public function isEqual(Act $act): bool
    {
        if ($act->songCollection()->count() != $this->songCollection()->count()) {
            return false;
        }

        foreach ($act->songCollection() as $key => $song) {
            if (!$song->isEqual($this->songCollection()[$key])) {
                return false;
            }
        }

        return true;
    }
}
