<?php

namespace Tests\Unit\Setlist\Infrastructure\DataTransformer;

use Setlist\Application\DataTransformer\SongDataTransformer;
use PHPUnit\Framework\TestCase;
use Setlist\Application\Persistence\Setlist\PersistedSetlist;
use Setlist\Application\Persistence\Song\PersistedSong;
use Setlist\Application\Persistence\Song\PersistedSongCollection;
use Setlist\Domain\Value\Uuid;
use Setlist\Infrastructure\DataTransformer\SetlistDataTransformer;

class SetlistDataTransformerTest extends TestCase
{
    /**
     * @test
     */
    public function dataTransformerCanBeWritenAndReaded()
    {
        $songs = [
            $this->getSongMock(),
            $this->getSongMock(),
        ];
        $acts = [
            PersistedSongCollection::create(...$songs),
        ];

        $id = Uuid::random();
        $name = 'Setlist name';
        $description = 'Setlist description';
        $date = '2018-01-01';
        $creationDate =
        $updateDate = '2018-01-01 00:00:00';
        $setlist = new PersistedSetlist(
            $id,
            $acts,
            $name,
            $description,
            $date,
            $creationDate,
            $updateDate
        );

        $songResult = ['Song data!'];
        $resultActs = [
            [
                $songResult,
                $songResult
            ],
        ];
        $songDataTransformer = $this->getMockBuilder(SongDataTransformer::class)->getMock();

        $songDataTransformer
            ->expects($this->any())
            ->method('write');
        $songDataTransformer
            ->expects($this->any())
            ->method('read')
            ->willReturn($songResult);

        $setlistDataTransfomer = new SetlistDataTransformer($songDataTransformer);
        $setlistDataTransfomer->write($setlist);

        $setlistArray = $setlistDataTransfomer->read();

        $this->assertInternalType(
            'array',
            $setlistArray
        );

        $this->assertEquals(
            $setlistArray['id'],
            $id->uuid()
        );

        $this->assertEquals(
            $setlistArray['name'],
            $name
        );

        $this->assertEquals(
            $setlistArray['description'],
            $description
        );

        $this->assertEquals(
            $setlistArray['date'],
            $date
        );

        $this->assertEquals(
            $setlistArray['acts'],
            $resultActs
        );

        $this->assertEquals(
            $setlistArray['creation_date'],
            $creationDate
        );

        $this->assertEquals(
            $setlistArray['update_date'],
            $updateDate
        );
    }

    private function getSongMock()
    {
        return $this->getMockBuilder(PersistedSong::class)->disableOriginalConstructor()->getMock();
    }
}
