<?php

namespace Tests\Unit\Setlist\Infrastructure\Messaging\Setlist;

use Setlist\Application\Persistence\Setlist\SetlistProjectorRepository;
use Setlist\Domain\Entity\Setlist\Event\SetlistChangedItsName;
use Setlist\Infrastructure\Messaging\EventHandler\Setlist\SetlistChangedItsNameHandler;
use PHPUnit\Framework\TestCase;

class SetlistChangedItsNameHandlerTest extends TestCase
{
    /**
     * @test
     */
    public function handlerCanBeInvoked()
    {
        $repository = $this->getMockBuilder(SetlistProjectorRepository::class)->getMock();
        $event = $this->getMockBuilder(SetlistChangedItsName::class)->disableOriginalConstructor()->getMock();
        $handler = new SetlistChangedItsNameHandler($repository);

        $repository->expects($this->once())
            ->method('changeName')
            ->with($event);

        ($handler)($event);
    }
}
