<?php

use Setlist\Application\DataTransformer\SongDataTransformer;
use PHPUnit\Framework\TestCase;
use Setlist\Domain\Entity\Song\Song;
use Setlist\Domain\Value\Uuid;

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

        $song = $this->getMockBuilder(Song::class)->getMock();
        $song->expects($this->once())
            ->method('id')
            ->willReturn($id);
        $song->expects($this->once())
            ->method('title')
            ->willReturn($title);
        $song->expects($this->once())
            ->method('formattedCreationDate')
            ->willReturn($creationDate);
        $song->expects($this->once())
            ->method('formattedUpdateDate')
            ->willReturn($updateDate);

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
