<?php

namespace Setlist\Domain\Entity\Setlist;

// VO of Setlist?
class Act
{
    private $songCollections;

    public function __construct(SongCollection ...$songCollections)
    {
        $this->songCollections = $songCollections;
    }

    public function songCollections(): array
    {
        return $this->songCollections;
    }
}
