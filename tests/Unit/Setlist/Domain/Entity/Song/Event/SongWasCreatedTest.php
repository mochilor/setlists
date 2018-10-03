<?php

namespace Tests\Unit\Setlist\Domain\Entity\Song\Event;

use Setlist\Domain\Entity\Song\Event\SongWasCreated;
use PHPUnit\Framework\TestCase;
use Setlist\Domain\Value\Uuid;

class SongWasCreatedTest extends TestCase
{
    /**
     * @test
     */
    public function domainEventCanBeCreatedAndHasGetters()
    {
        $uuid = Uuid::random();
        $title = 'A Title';
        $formattedDateTime = '2018-01-01 00:00:00';
        $event = SongWasCreated::create($uuid, $title, $formattedDateTime);

        $this->assertInstanceOf(
            SongWasCreated::class,
            $event
        );

        $this->assertEquals(
            $uuid,
            $event->id()
        );

        $this->assertEquals(
            $title,
            $event->title()
        );

        $this->assertEquals(
            $formattedDateTime,
            $event->formattedDateTime()
        );

        $this->assertInternalType(
            'int',
            $event->occurredOn()
        );
    }
}
