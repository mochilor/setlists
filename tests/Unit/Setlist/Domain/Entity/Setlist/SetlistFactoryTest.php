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
use Setlist\Domain\Value\UuidGenerator;

class SetlistFactoryTest extends TestCase
{
    /**
     * @test
     */
    public function factoryCanMakeInstances()
    {
        $uuid = '8ffd680a-ff57-41f3-ac5e-bf1d877f6950';
        $uuidObject = $this->getMockBuilder(Uuid::class)->getMock();
        $acts = [
            $this->getAct(),
            $this->getAct(),
            $this->getAct(),
        ];
        $name = 'Name';
        $description = 'Description';
        $formattedDate = '2018-10-01';

        $eventBus = $this->getMockBuilder(EventBus::class)->getMock();
        $eventsTrigger = new EventsTrigger($eventBus);
        $uuidGenerator = $this->getMockBuilder(UuidGenerator::class)->getMock();
        $uuidGenerator
            ->expects($this->once())
            ->method('fromString')
            ->with($uuid)
            ->willReturn($uuidObject);

        $factory = new SetlistFactory($eventsTrigger, $uuidGenerator);
        $setlist = $factory->make($uuid, $acts, $name, $description, $formattedDate);

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

    /**
     * @test
     */
    public function factoryCanRestoreInstances()
    {
        $uuid = '8ffd680a-ff57-41f3-ac5e-bf1d877f6950';
        $uuidObject = $this->getMockBuilder(Uuid::class)->getMock();
        $acts = [
            $this->getAct(),
            $this->getAct(),
            $this->getAct(),
        ];
        $name = 'Name';
        $description = 'Description';
        $formattedDate = '2018-10-01';
        $formattedCreationDate = '2018-10-01 15:00:00';
        $formattedUpdateDate = '2018-10-01 15:00:00';
        $eventBus = $this->getMockBuilder(EventBus::class)->getMock();
        $uuidGenerator = $this->getMockBuilder(UuidGenerator::class)->getMock();
        $uuidGenerator
            ->expects($this->once())
            ->method('fromString')
            ->with($uuid)
            ->willReturn($uuidObject);

        $eventsTrigger = new EventsTrigger($eventBus);

        $factory = new SetlistFactory($eventsTrigger, $uuidGenerator);
        $setlist = $factory->restore($uuid, $acts, $name, $description, $formattedDate, $formattedCreationDate, $formattedUpdateDate);

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
        $uuid = '8ffd680a-ff57-41f3-ac5e-bf1d877f6950';
        $uuidObject = $this->getMockBuilder(Uuid::class)->getMock();
        $acts = [
            $this->getAct(),
            $this->getAct(),
            $this->getAct(),
        ];
        $name = 'Name';
        $description = 'Description';
        $formattedDate = 'Random string!';

        $eventBus = $this->getMockBuilder(EventBus::class)->getMock();
        $eventsTrigger = new EventsTrigger($eventBus);
        $uuidGenerator = $this->getMockBuilder(UuidGenerator::class)->getMock();
        $uuidGenerator
            ->expects($this->once())
            ->method('fromString')
            ->with($uuid)
            ->willReturn($uuidObject);

        $factory = new SetlistFactory($eventsTrigger, $uuidGenerator);
        $factory->make($uuid, $acts, $name, $description, $formattedDate);
    }

    protected function getAct()
    {
        return $this->getMockBuilder(Act::class)->getMock();
    }
}
