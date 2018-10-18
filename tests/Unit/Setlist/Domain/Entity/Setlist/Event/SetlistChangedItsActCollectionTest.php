<?php

namespace Tests\Unit\Setlist\Domain\Entity\Setlist\Event;

use Setlist\Domain\Entity\Setlist\ActCollection;
use Setlist\Domain\Entity\Setlist\Event\SetlistChangedItsActCollection;
use PHPUnit\Framework\TestCase;
use Setlist\Domain\Value\Uuid;

class SetlistChangedItsActCollectionTest extends TestCase
{
    /**
     * @test
     */
    public function domainEventCanBeCreatedAndHasGetters()
    {
        $uuid = Uuid::random();
        $actCollection = ActCollection::create(...[]);
        $formattedUpdateDate = '2018-01-01';
        $event = SetlistChangedItsActCollection::create($uuid, $actCollection, $formattedUpdateDate);

        $this->assertInstanceOf(
            SetlistChangedItsActCollection::class,
            $event
        );

        $this->assertEquals(
            $uuid,
            $event->id()
        );

        $this->assertEquals(
            $formattedUpdateDate,
            $event->formattedUpdateDate()
        );

        $this->assertEquals(
            $actCollection,
            $event->actCollection()
        );
    }
}
