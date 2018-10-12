<?php

namespace Tests\Unit\Setlist\Domain\Entity\Song;

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

        $eventsTrigger = new EventsTrigger();

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
        $eventsTrigger = new EventsTrigger();

        $factory = new SetlistFactory($eventsTrigger);
        $setlist = $factory->restore($uuid, $acts, $name, $formattedDate);

        $this->assertInstanceOf(
            Setlist::class,
            $setlist
        );

        $this->assertCount(
            0,
            $setlist->events()
        );
    }
}
