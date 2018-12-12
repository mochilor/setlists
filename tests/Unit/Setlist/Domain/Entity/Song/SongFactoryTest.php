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
use Setlist\Domain\Value\UuidGenerator;

class SongFactoryTest extends TestCase
{
    /**
     * @test
     */
    public function factoryCanMakeInstances()
    {
        $uuid = '8ffd680a-ff57-41f3-ac5e-bf1d877f6950';
        $uuidObject = $this->getMockBuilder(Uuid::class)->getMock();
        $title = 'Title';
        $eventBus = $this->getMockBuilder(EventBus::class)->getMock();
        $eventsTrigger = new EventsTrigger($eventBus);
        $uuidGenerator = $this->getMockBuilder(UuidGenerator::class)->getMock();
        $uuidGenerator
            ->expects($this->once())
            ->method('fromString')
            ->with($uuid)
            ->willReturn($uuidObject);

        $factory = new SongFactory($eventsTrigger, $uuidGenerator);
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
        $uuid = '8ffd680a-ff57-41f3-ac5e-bf1d877f6950';
        $uuidObject = $this->getMockBuilder(Uuid::class)->getMock();
        $title = 'Title';
        $eventBus = $this->getMockBuilder(EventBus::class)->getMock();
        $eventsTrigger = new EventsTrigger($eventBus);
        $dateTime = DateTimeImmutable::createFromFormat(Song::CREATION_DATE_FORMAT, '2018-01-01 00:00:00');
        $song = Song::restore($uuidObject, $title, true, $dateTime, $dateTime, $eventsTrigger);
        $uuidGenerator = $this->getMockBuilder(UuidGenerator::class)->getMock();
        $uuidGenerator
            ->expects($this->once())
            ->method('fromString')
            ->with($uuid)
            ->willReturn($uuidObject);

        $factory = new SongFactory($eventsTrigger, $uuidGenerator);
        $formattedCreationDate = $dateTime->format(Song::CREATION_DATE_FORMAT);
        $formattedUpdateDate = $dateTime->format(Song::UPDATE_DATE_FORMAT);

        $this->assertEquals(
            $song,
            $factory->restore($uuid, $title, true, $formattedCreationDate, $formattedUpdateDate)
        );

        $this->assertCount(
            0,
            $song->events()
        );
    }
}
