<?php

namespace Tests\Unit\Setlist\Application\Persistence\Song;

use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;
use Setlist\Application\Persistence\Song\PersistedSong;
use Setlist\Application\Persistence\Song\PersistedSongCollection;
use Setlist\Application\Persistence\Song\PersistedSongCollectionFactory;

class PersistedSongCollectionFactoryTest extends TestCase
{
    /**
     * @test
     */
    public function factoryCanMakeInstances()
    {
        $persistedSong1 = $this->getPersistedSong();
        $persistedSong2 = $this->getPersistedSong();
        $persistedSong3 = $this->getPersistedSong();
        $persistedSongsArray = [
            15 => $persistedSong1,
            222 => $persistedSong2,
            2 => $persistedSong3,
        ];

        $persistedSongCollectionFactory = new PersistedSongCollectionFactory();
        $persistedSongCollection = $persistedSongCollectionFactory->make($persistedSongsArray);

        $this->assertEquals(
            $persistedSong3,
            $persistedSongCollection[0]
        );

        $this->assertEquals(
            $persistedSong1,
            $persistedSongCollection[1]
        );

        $this->assertEquals(
            $persistedSong2,
            $persistedSongCollection[2]
        );
    }

    private function getPersistedSong(): PersistedSong
    {
        $id = Uuid::uuid4();

        return new PersistedSong(
            $id,
            'Title',
            1,
            '2018-01-01 10:00:00',
            '2018-01-01 10:00:00'
        );
    }
}
