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
        $this->assertInstanceOf(
            Song::class,
            $this->getSong()
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
     * @expectedException \Setlist\Domain\Exception\Song\InvalidTitleException
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
    }
}
