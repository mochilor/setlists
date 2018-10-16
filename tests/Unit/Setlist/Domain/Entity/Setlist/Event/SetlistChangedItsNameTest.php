<?php

namespace Tests\Unit\Setlist\Domain\Entity\Setlist\Event;

use Setlist\Domain\Entity\Setlist\Event\SetlistChangedItsName;
use PHPUnit\Framework\TestCase;
use Setlist\Domain\Value\Uuid;

class SetlistChangedItsNameTest extends TestCase
{
    /**
     * @test
     */
    public function domainEventCanBeCreatedAndHasGetters()
    {
        $uuid = Uuid::random();
        $name = 'A Name';
        $formattedUpdateDate = '2018-01-01';
        $event = SetlistChangedItsName::create($uuid, $name, $formattedUpdateDate);

        $this->assertInstanceOf(
            SetlistChangedItsName::class,
            $event
        );

        $this->assertEquals(
            $uuid,
            $event->id()
        );

        $this->assertEquals(
            $name,
            $event->name()
        );

        $this->assertEquals(
            $formattedUpdateDate,
            $event->formattedUpdateDate()
        );
    }
}
