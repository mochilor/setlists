<?php

namespace Tests\Unit\Setlist\Domain\Entity\Setlist;

use PHPUnit\Framework\TestCase;
use Setlist\Domain\Entity\Setlist\SongCollection;
use Setlist\Domain\Entity\Song\Song;

class SongCollectionTest extends TestCase
{
    /**
     * @test
     */
    public function songCollectionCanBeCreated()
    {
        $songs = [
            $this->getSong(),
            $this->getSong(),
            $this->getSong(),
            $this->getSong(),
        ];

        $this->assertInstanceOf(
            SongCollection::class,
            SongCollection::create(...$songs)
        );
    }

    private function getSong()
    {
        return $this->getMockBuilder(Song::class)->getMock();
    }
}
