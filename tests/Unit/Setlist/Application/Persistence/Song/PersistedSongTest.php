<?php

namespace Tests\Unit\Setlist\Application\Persistence\Song;

use PHPUnit\Framework\TestCase;
use Setlist\Application\Persistence\Song\PersistedSong;

class PersistedSongTest extends TestCase
{
    /**
     * @test
     */
    public function persistedSongCanBeCreated()
    {
        $id = '550e8400-e29b-41d4-a716-446655440000';
        $title = 'Name';
        $creationDate = '2018-01-01 10:00:00';
        $updateDate = '2018-01-01 10:00:00';

        $persistedSong = new PersistedSong($id, $title, 1, $creationDate, $updateDate);

        $this->assertInstanceOf(
            PersistedSong::class,
            $persistedSong
        );

        $this->assertEquals(
            $id,
            $persistedSong->id()
        );

        $this->assertEquals(
            $title,
            $persistedSong->title()
        );

        $this->assertEquals(
            $creationDate,
            $persistedSong->creationDate()
        );

        $this->assertEquals(
            $updateDate,
            $persistedSong->updateDate()
        );
    }
}
