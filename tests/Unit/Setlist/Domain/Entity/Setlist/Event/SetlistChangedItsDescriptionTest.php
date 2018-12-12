<?php

namespace Tests\Unit\Setlist\Domain\Entity\Setlist\Event;

use Setlist\Domain\Entity\Setlist\Event\SetlistChangedItsDescription;
use PHPUnit\Framework\TestCase;
use Setlist\Domain\Value\Uuid;

class SetlistChangedItsDescriptionTest extends TestCase
{
    /**
     * @test
     */
    public function domainEventCanBeCreatedAndHasGetters()
    {
        $uuid = $this->getMockBuilder(Uuid::class)->getMock();
        $description = 'A Description';
        $formattedUpdateDate = '2018-01-01';
        $event = SetlistChangedItsDescription::create($uuid, $description, $formattedUpdateDate);

        $this->assertInstanceOf(
            SetlistChangedItsDescription::class,
            $event
        );

        $this->assertEquals(
            $uuid,
            $event->id()
        );

        $this->assertEquals(
            $description,
            $event->description()
        );

        $this->assertEquals(
            $formattedUpdateDate,
            $event->formattedUpdateDate()
        );
    }
}
