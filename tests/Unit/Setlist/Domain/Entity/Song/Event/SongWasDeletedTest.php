<?php

namespace Tests\Unit\Setlist\Domain\Entity\Song\Event;

use PHPUnit\Framework\TestCase;
use Setlist\Domain\Entity\Song\Event\SongWasDeleted;
use Setlist\Domain\Value\Uuid;

class SongWasDeletedTest extends TestCase
{
    /**
     * @test
     */
    public function domainEventCanBeCreatedAndHasGetters()
    {
        $uuid = $this->getMockBuilder(Uuid::class)->getMock();
        $event = SongWasDeleted::create($uuid);

        $this->assertInstanceOf(
            SongWasDeleted::class,
            $event
        );

        $this->assertEquals(
            $uuid,
            $event->id()
        );
    }
}
