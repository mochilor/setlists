<?php

namespace Tests\Setlist\Domain\Entity\Song;

use Setlist\Domain\Entity\Song\Song;
use PHPUnit\Framework\TestCase;
use Setlist\Domain\Value\Uuid;

class SongTest extends TestCase
{
    const SONG_NAME = 'Song title';

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
            self::SONG_NAME
        );
    }

    /**
     * @test
     * @expectedException \Setlist\Domain\Exception\Song\InvalidNameException
     */
    public function badNameThrowsException()
    {
        $uuid = Uuid::random();
        $name = 'A';

        Song::create($uuid, $name);
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
            self::SONG_NAME,
            $song->name()
        );
    }

    /**
     * @test
     */
    public function songCanChangeItsName()
    {
        $song = $this->getSong();

        $newName = 'New song name';
        $song->changeName($newName);

        $this->assertEquals(
            $newName,
            $song->name()
        );
    }
}
