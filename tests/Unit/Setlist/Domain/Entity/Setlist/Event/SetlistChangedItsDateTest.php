<?php

namespace Tests\Unit\Setlist\Domain\Entity\Setlist\Event;

use Setlist\Domain\Entity\Setlist\Event\SetlistChangedItsDate;
use PHPUnit\Framework\TestCase;
use Setlist\Domain\Value\Uuid;

class SetlistChangedItsDateTest extends TestCase
{
    /**
     * @test
     */
    public function domainEventCanBeCreatedAndHasGetters()
    {
        // public static function create(Uuid $id, string $formattedDate, string $formattedUpdateDate): self
        $uuid = Uuid::random();
        $formattedDate = $formattedUpdateDate = '2018-01-01';
        $event = SetlistChangedItsDate::create($uuid, $formattedDate, $formattedUpdateDate);

        $this->assertInstanceOf(
            SetlistChangedItsDate::class,
            $event
        );

        $this->assertEquals(
            $uuid,
            $event->id()
        );

        $this->assertEquals(
            $formattedDate,
            $event->formattedDate()
        );

        $this->assertEquals(
            $formattedUpdateDate,
            $event->formattedUpdateDate()
        );
    }
}
