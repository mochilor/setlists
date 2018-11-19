<?php

namespace Tests\Unit\Setlist\Infrastructure\DataTransformer;

use PHPUnit\Framework\TestCase;
use Setlist\Application\Persistence\Song\PersistedSong;
use Setlist\Domain\Entity\Song\Song;
use Setlist\Domain\Value\Uuid;
use Setlist\Infrastructure\DataTransformer\SongDataTransformer;

class SongDataTransformerTest extends TestCase
{
    /**
     * @test
     */
    public function dataTransformerCanBeWritenAndReaded()
    {
        $id = Uuid::random();
        $title = 'Song Title';
        $creationDate = '2018-10-10 10:11:22';
        $updateDate = '2018-10-10 10:11:22';

        $song = new PersistedSong(
            $id,
            $title,
            $creationDate,
            $updateDate
        );

        $songDataTransformer = new SongDataTransformer();
        $songDataTransformer->write($song);

        $songArray = $songDataTransformer->read();

        $this->assertInternalType(
            'array',
            $songArray
        );

        $this->assertEquals(
            $songArray['id'],
            $id->uuid()
        );

        $this->assertEquals(
            $songArray['title'],
            $title
        );

        $this->assertEquals(
            $songArray['creation_date'],
            $creationDate
        );

        $this->assertEquals(
            $songArray['update_date'],
            $updateDate
        );
    }
}
