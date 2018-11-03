<?php

namespace Tests\Unit\Setlist\Domain\Entity\Song;

use DateTimeImmutable;
use Setlist\Domain\Entity\EventBus;
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
        $eventBus = $this->getMockBuilder(EventBus::class)->getMock();
        $eventsTrigger = new EventsTrigger($eventBus);
        $factory = new SongFactory($eventsTrigger);
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
        $eventBus = $this->getMockBuilder(EventBus::class)->getMock();
        $eventsTrigger = new EventsTrigger($eventBus);
        $dateTime = DateTimeImmutable::createFromFormat(Song::CREATION_DATE_FORMAT, '2018-01-01 00:00:00');
        $song = Song::restore($uuid, $title, $dateTime, $dateTime, $eventsTrigger);
        $factory = new SongFactory($eventsTrigger);
        $formattedCreationDate = $dateTime->format(Song::CREATION_DATE_FORMAT);
        $formattedUpdateDate = $dateTime->format(Song::UPDATE_DATE_FORMAT);

        $this->assertEquals(
            $song,
            $factory->restore($uuid, $title, $formattedCreationDate, $formattedUpdateDate)
        );

        $this->assertCount(
            0,
            $song->events()
        );
    }
}
