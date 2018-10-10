<?php

namespace Tests\Unit\Setlist\Domain\Entity\Setlist;

use Setlist\Domain\Entity\Setlist\Act;
use PHPUnit\Framework\TestCase;
use Setlist\Domain\Entity\Setlist\SongCollection;
use Setlist\Domain\Entity\Song\Song;

class ActTest extends TestCase
{
    /**
     * @test
     */
    public function ActCanBeCreated()
    {
        $act = $this->getAct(5);

        $this->assertInstanceOf(
            Act::class,
            $act
        );
    }

    /**
     * @test
     * @dataProvider isEqualDataProvider
     */
    public function actCanBeCompared($act1Songs, $act2Songs, $isEqual, $result, $message)
    {
        $act = $this->getAct($act1Songs, $isEqual);
        $otherAct = $this->getAct($act2Songs, $isEqual);

        $this->assertEquals(
            $result,
            $act->isEqual($otherAct),
            $message
        );
    }

    public function isEqualDataProvider()
    {
        return [
            [3, 3, true, true, 'Same number of songs and same titles'],
            [3, 3, false, false, 'Same number of songs and different titles'],
            [5, 3, true, false, 'Different number of songs and same titles'],
            [5, 3, false, false, 'Different number of songs and different titles'],
        ];
    }

    /**
     * @test
     * @expectedException \Setlist\Domain\Exception\Setlist\InvalidSongCollectionException
     */
    public function testEmptySongCollectionThrowsException()
    {
        $this->getAct(0);
    }

    private function getAct($numberOfSongs, $isEqual = true): Act
    {
        $songCollection = $this->getSongCollection($numberOfSongs, $isEqual);
        return Act::create($songCollection);
    }

    private function getSongCollection($numberOfSongs, $isEqual)
    {
        $songs = [];

        for ($n = 0; $n < $numberOfSongs; $n++) {
            $songs[] = $this->getSong($isEqual);
        }

        return SongCollection::create(...$songs);
    }

    private function getSong($isEqual)
    {
        $song = $this->getMockBuilder(Song::class)->getMock();

        $song->expects($this->any())
            ->method('isEqual')
            ->willReturn($isEqual);

        return $song;
    }
}
