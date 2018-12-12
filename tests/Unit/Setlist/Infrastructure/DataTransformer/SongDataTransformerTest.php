<?php

namespace Tests\Unit\Setlist\Infrastructure\DataTransformer;

use PHPUnit\Framework\TestCase;
use Setlist\Application\Persistence\Song\PersistedSong;
use Setlist\Infrastructure\DataTransformer\SongDataTransformer;

class SongDataTransformerTest extends TestCase
{
    /**
     * @test
     */
    public function dataTransformerCanBeWritenAndReaded()
    {
        $id = '8ffd680a-ff57-41f3-ac5e-bf1d877f6950';
        $title = 'Song Title';
        $creationDate = '2018-10-10 10:11:22';
        $updateDate = '2018-10-10 10:11:22';

        $song = new PersistedSong(
            $id,
            $title,
            1,
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
            $id
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
