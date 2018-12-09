<?php

namespace Tests\Unit\Setlist\Infrastructure\Messaging\Setlist;

use Setlist\Application\Persistence\Setlist\SetlistProjectorRepository;
use Setlist\Domain\Entity\Setlist\Event\SetlistChangedItsDate;
use Setlist\Infrastructure\Messaging\EventHandler\Setlist\SetlistChangedItsDateHandler;
use PHPUnit\Framework\TestCase;

class SetlistChangedItsDateHandlerTest extends TestCase
{
    /**
     * @test
     */
    public function handlerCanBeInvoked()
    {
        $repository = $this->getMockBuilder(SetlistProjectorRepository::class)->getMock();
        $event = $this->getMockBuilder(SetlistChangedItsDate::class)->disableOriginalConstructor()->getMock();
        $handler = new SetlistChangedItsDateHandler($repository);

        $repository->expects($this->once())
            ->method('changeDate')
            ->with($event);

        ($handler)($event);
    }
}
