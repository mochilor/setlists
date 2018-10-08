<?php

namespace Tests\Unit\Setlist\Domain\Entity\Song;

use DateTimeImmutable;
use Setlist\Domain\Entity\EventsTrigger;
use Setlist\Domain\Entity\Song\Event\SongWasCreated;
use Setlist\Domain\Entity\Song\Song;
use PHPUnit\Framework\TestCase;
use Setlist\Domain\Entity\Song\SongFactory;
use Setlist\Domain\Value\Uuid;

class SongFactoryTest extends TestCase
{
    /**
     * @test
     */
    public function factoryCanMakeInstances()
    {
        $uuid = Uuid::random();
        $title = 'Title';
        $eventsTrigger = new EventsTrigger();
        $dateTime = new DateTimeImmutable();
        $eventsTrigger->trigger(SongWasCreated::create(
            $uuid,
            $title,
            $dateTime->format(Song::DATE_TIME_FORMAT)
        ));
        $factory = new SongFactory(new EventsTrigger());
        $song = $factory->make($uuid, $title);

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

    /**
     * @test
     */
    public function factoryCanRestoreInstances()
    {
        $uuid = Uuid::random();
        $title = 'Title';
        $eventsTrigger = new EventsTrigger();
        $dateTime = DateTimeImmutable::createFromFormat(Song::DATE_TIME_FORMAT, '2018-01-01 00:00:00');
        $song = Song::create($uuid, $title, $dateTime, $eventsTrigger);
        $factory = new SongFactory(new EventsTrigger());

        $this->assertEquals(
            $song,
            $factory->restore($uuid, $title, $dateTime->format(Song::DATE_TIME_FORMAT))
        );

        $this->assertCount(
            0,
            $song->events()
        );
    }
}
