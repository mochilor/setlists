<?php

namespace Tests\Unit\Setlist\Domain\Entity\Song;

use Setlist\Domain\Entity\EventBus;
use Setlist\Domain\Entity\EventsTrigger;
use Setlist\Domain\Entity\Setlist\Act;
use Setlist\Domain\Entity\Setlist\Event\SetlistWasCreated;
use Setlist\Domain\Entity\Setlist\Setlist;
use Setlist\Domain\Entity\Setlist\SetlistFactory;
use PHPUnit\Framework\TestCase;
use Setlist\Domain\Value\Uuid;

class SetlistFactoryTest extends TestCase
{
    /**
     * @test
     */
    public function factoryCanMakeInstances()
    {
        $uuid = Uuid::random();
        $acts = [
            $this->getAct(),
            $this->getAct(),
            $this->getAct(),
        ];
        $name = 'Name';
        $formattedDate = '2018-10-01';

        $eventBus = $this->getMockBuilder(EventBus::class)->getMock();
        $eventsTrigger = new EventsTrigger($eventBus);

        $factory = new SetlistFactory($eventsTrigger);
        $setlist = $factory->make($uuid, $acts, $name, $formattedDate);

        $this->assertInstanceOf(
            Setlist::class,
            $setlist
        );

        $this->assertCount(
            1,
            $setlist->events()
        );

        $this->assertInstanceOf(
            SetlistWasCreated::class,
            $setlist->events()[0]
        );
    }

    protected function getAct()
    {
        return $this->getMockBuilder(Act::class)->getMock();
    }

    /**
     * @test
     */
    public function factoryCanRestoreInstances()
    {
        $uuid = Uuid::random();
        $acts = [
            $this->getAct(),
            $this->getAct(),
            $this->getAct(),
        ];
        $name = 'Name';
        $formattedDate = '2018-10-01';
        $formattedCreationDate = '2018-10-01 15:00:00';
        $formattedUpdateDate = '2018-10-01 15:00:00';
        $eventBus = $this->getMockBuilder(EventBus::class)->getMock();
        $eventsTrigger = new EventsTrigger($eventBus);

        $factory = new SetlistFactory($eventsTrigger);
        $setlist = $factory->restore($uuid, $acts, $name, $formattedDate, $formattedCreationDate, $formattedUpdateDate);

        $this->assertInstanceOf(
            Setlist::class,
            $setlist
        );

        $this->assertCount(
            0,
            $setlist->events()
        );
    }

    /**
     * @test
     * @expectedException \Setlist\Domain\Exception\Setlist\InvalidDateException
     */
    public function invalidDateThrowsException()
    {
        $uuid = Uuid::random();
        $acts = [
            $this->getAct(),
            $this->getAct(),
            $this->getAct(),
        ];
        $name = 'Name';
        $formattedDate = 'Random string!';

        $eventBus = $this->getMockBuilder(EventBus::class)->getMock();
        $eventsTrigger = new EventsTrigger($eventBus);

        $factory = new SetlistFactory($eventsTrigger);
        $factory->make($uuid, $acts, $name, $formattedDate);
    }
}
