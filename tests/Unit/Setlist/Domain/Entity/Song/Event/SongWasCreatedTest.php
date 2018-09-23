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
        $event = SongWasCreated::create($uuid, $title);

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

        $this->assertInternalType(
            'int',
            $event->occurredOn()
        );
    }
}
