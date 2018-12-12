<?php

namespace Tests\Unit\Setlist\Domain\Entity\Setlist\Event;

use PHPUnit\Framework\TestCase;
use Setlist\Domain\Entity\Setlist\Event\SetlistWasDeleted;
use Setlist\Domain\Value\Uuid;

class SetlistWasDeletedTest extends TestCase
{
    /**
     * @test
     */
    public function domainEventCanBeCreatedAndHasGetters()
    {
        $uuid = $this->getMockBuilder(Uuid::class)->getMock();
        $event = SetlistWasDeleted::create($uuid);

        $this->assertInstanceOf(
            SetlistWasDeleted::class,
            $event
        );

        $this->assertEquals(
            $uuid,
            $event->id()
        );
    }
}
