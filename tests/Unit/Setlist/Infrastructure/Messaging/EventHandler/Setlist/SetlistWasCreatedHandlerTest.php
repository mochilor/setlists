<?php

namespace Tests\Unit\Setlist\Infrastructure\Messaging\Setlist;

use Setlist\Application\Persistence\Setlist\SetlistProjectorRepository;
use Setlist\Domain\Entity\Setlist\Event\SetlistWasCreated;
use PHPUnit\Framework\TestCase;
use Setlist\Infrastructure\Messaging\EventHandler\Setlist\SetlistWasCreatedHandler;

class SetlistWasCreatedHandlerTest extends TestCase
{
    /**
     * @test
     */
    public function handlerCanBeInvoked()
    {
        $repository = $this->getMockBuilder(SetlistProjectorRepository::class)->getMock();
        $event = $this->getMockBuilder(SetlistWasCreated::class)->disableOriginalConstructor()->getMock();
        $handler = new SetlistWasCreatedHandler($repository);

        $repository->expects($this->once())
            ->method('save')
            ->with($event);

        ($handler)($event);
    }
}
