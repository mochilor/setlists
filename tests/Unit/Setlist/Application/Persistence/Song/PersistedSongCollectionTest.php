<?php

namespace Tests\Unit\Setlist\Application\Persistence\Song;

use PHPUnit\Framework\TestCase;
use Setlist\Application\Persistence\Song\PersistedSong;
use Setlist\Application\Persistence\Song\PersistedSongCollection;

class PersistedSongCollectionTest extends TestCase
{
    /**
     * @test
     */
    public function persistedSongCollectionCanBeCreated()
    {
        $persistedSong = $this->getMockBuilder(PersistedSong::class)
            ->disableOriginalConstructor()
            ->getMock();

        $persistedSongCollection = PersistedSongCollection::create($persistedSong);

        $this->assertInstanceOf(
            PersistedSongCollection::class,
            $persistedSongCollection
        );

        $this->assertSame(
            $persistedSong,
            $persistedSongCollection[0]
        );
    }
}
