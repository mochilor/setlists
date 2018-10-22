<?php

namespace Tests\Unit\Setlist\Domain\Entity\Song;

use DateTimeImmutable;
use Setlist\Domain\Entity\EventBus;
use Setlist\Domain\Entity\EventsTrigger;
use Setlist\Domain\Entity\Song\Event\SongChangedItsTitle;
use Setlist\Domain\Entity\Song\Event\SongWasCreated;
use Setlist\Domain\Entity\Song\Event\SongWasDeleted;
use Setlist\Domain\Entity\Song\Song;
use PHPUnit\Framework\TestCase;
use Setlist\Domain\Value\Uuid;

class SongTest extends TestCase
{
    const SONG_TITLE = 'Song title';
    const SONG_DATE_TIME = '2018-01-01 00:00:00';

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

        $this->assertInstanceOf(
            SongWasCreated::class,
            $song->events()[0]
        );
    }

    private function getSong(): Song
    {
        $eventBus = $this->getMockBuilder(EventBus::class)->getMock();
        $eventsTrigger = new EventsTrigger($eventBus);
        $uuid = Uuid::random();
        $title = self::SONG_TITLE;
        $eventsTrigger->trigger(SongWasCreated::create($uuid, $title, self::SONG_DATE_TIME));

        return Song::create(
            $uuid,
            $title,
            DateTimeImmutable::createFromFormat(Song::CREATION_DATE_FORMAT, self::SONG_DATE_TIME),
            DateTimeImmutable::createFromFormat(Song::UPDATE_DATE_FORMAT, self::SONG_DATE_TIME),
            $eventsTrigger
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

        $eventBus = $this->getMockBuilder(EventBus::class)->getMock();
        $eventsTrigger = new EventsTrigger($eventBus);
        $eventsTrigger->trigger(SongWasCreated::create($uuid, $title, self::SONG_DATE_TIME));

        Song::create(
            $uuid,
            $title,
            DateTimeImmutable::createFromFormat(Song::CREATION_DATE_FORMAT, self::SONG_DATE_TIME),
            DateTimeImmutable::createFromFormat(Song::UPDATE_DATE_FORMAT, self::SONG_DATE_TIME),
            $eventsTrigger
        );
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

        $this->assertInstanceOf(
            SongChangedItsTitle::class,
            $song->events()[1]
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
        $eventBus = $this->getMockBuilder(EventBus::class)->getMock();
        $eventsTrigger = new EventsTrigger($eventBus);
        $uuid = Uuid::random();
        $title = 'Another song title';
        $eventsTrigger->trigger(SongWasCreated::create($uuid, $title, self::SONG_DATE_TIME));

        return Song::create(
            $uuid,
            $title,
            DateTimeImmutable::createFromFormat(Song::CREATION_DATE_FORMAT, self::SONG_DATE_TIME),
            DateTimeImmutable::createFromFormat(Song::UPDATE_DATE_FORMAT, self::SONG_DATE_TIME),
            $eventsTrigger
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

        $this->assertInstanceOf(
            SongWasDeleted::class,
            $song->events()[1]
        );
    }

    /**
     * @test
     */
    public function songHasCreationDate()
    {
        $song = $this->getSong();
        $dateTime = DateTimeImmutable::createFromFormat(Song::CREATION_DATE_FORMAT, self::SONG_DATE_TIME);

        $this->assertEquals(
            $dateTime,
            $song->creationDate()
        );
    }

    /**
     * @test
     */
    public function songHasFormattedCreationDate()
    {
        $song = $this->getSong();

        $this->assertEquals(
            self::SONG_DATE_TIME,
            $song->formattedCreationDate()
        );
    }

    /**
     * @test
     */
    public function songHasUpdateDate()
    {
        $song = $this->getSong();
        $dateTime = DateTimeImmutable::createFromFormat(Song::UPDATE_DATE_FORMAT, self::SONG_DATE_TIME);

        $this->assertEquals(
            $dateTime,
            $song->updateDate()
        );
    }

    /**
     * @test
     */
    public function songHasFormattedUpdateDate()
    {
        $song = $this->getSong();

        $this->assertEquals(
            self::SONG_DATE_TIME,
            $song->formattedUpdateDate()
        );
    }
}
