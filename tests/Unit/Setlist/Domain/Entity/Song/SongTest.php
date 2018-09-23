<?php

namespace Tests\Unit\Setlist\Domain\Entity\Song;

use Setlist\Domain\Entity\Song\Song;
use PHPUnit\Framework\TestCase;
use Setlist\Domain\Value\Uuid;

class SongTest extends TestCase
{
    const SONG_TITLE = 'Song title';

    /**
     * @test
     */
    public function songCanBeCreated()
    {
        $song = $this->getSong();
        $this->assertInstanceOf(
            Song::class,
            $song
        );

        $this->assertCount(
            1,
            $song->events()
        );
    }

    private function getSong(): Song
    {
        return Song::create(
            Uuid::random(),
            self::SONG_TITLE
        );
    }

    /**
     * @test
     * @expectedException \Setlist\Domain\Exception\Song\InvalidSongTitleException
     */
    public function badTitleThrowsException()
    {
        $uuid = Uuid::random();
        $title = 'A';

        Song::create($uuid, $title);
    }

    /**
     * @test
     */
    public function songHasId()
    {
        $song = $this->getSong();

        $this->assertInstanceOf(
            Uuid::class,
            $song->id()
        );
    }

    /**
     * @test
     */
    public function songHasName()
    {
        $song = $this->getSong();

        $this->assertEquals(
            self::SONG_TITLE,
            $song->title()
        );
    }

    /**
     * @test
     */
    public function songCanChangeItsName()
    {
        $song = $this->getSong();

        $newName = 'New song name';
        $song->changeTitle($newName);

        $this->assertEquals(
            $newName,
            $song->title()
        );

        $this->assertCount(
            2,
            $song->events()
        );
    }

    /**
     * @test
     */
    public function sameTitleDoesNotTriggerEvent()
    {
        $song = $this->getSong();

        $newName = self::SONG_TITLE;
        $song->changeTitle($newName);

        $this->assertEquals(
            $newName,
            $song->title()
        );

        $this->assertCount(
            1,
            $song->events()
        );
    }

    /**
     * @test
     */
    public function songsCanBeEquals()
    {
        $aSong = $this->getSong();
        $anotherSong = $this->getSong();

        $this->assertTrue(
            $aSong->isEqual($anotherSong)
        );
    }

    /**
     * @test
     */
    public function songsCanBeDifferent()
    {
        $aSong = $this->getSong();
        $anotherSong = $this->getAnotherSong();

        $this->assertTrue(
            !$aSong->isEqual($anotherSong)
        );
    }

    private function getAnotherSong(): Song
    {
        return Song::create(
            Uuid::random(),
            'Another song title'
        );
    }

    /**
     * @test
     */
    public function songCanBeDeleted()
    {
        $song = $this->getSong();
        $song->delete();

        $this->assertCount(
            2,
            $song->events()
        );
    }
}
