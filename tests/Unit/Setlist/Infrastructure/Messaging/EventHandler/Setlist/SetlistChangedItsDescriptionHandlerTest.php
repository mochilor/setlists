<?php

namespace Tests\Unit\Setlist\Infrastructure\Messaging\Setlist;

use Setlist\Application\Persistence\Setlist\SetlistProjectorRepository;
use Setlist\Domain\Entity\Setlist\Event\SetlistChangedItsDescription;
use Setlist\Infrastructure\Messaging\EventHandler\Setlist\SetlistChangedItsDescriptionHandler;
use PHPUnit\Framework\TestCase;

class SetlistChangedItsDescriptionHandlerTest extends TestCase
{
    /**
     * @test
     */
    public function handlerCanBeInvoked()
    {
        $repository = $this->getMockBuilder(SetlistProjectorRepository::class)->getMock();
        $event = $this->getMockBuilder(SetlistChangedItsDescription::class)->disableOriginalConstructor()->getMock();
        $handler = new SetlistChangedItsDescriptionHandler($repository);

        $repository->expects($this->once())
            ->method('changeDescription')
            ->with($event);

        ($handler)($event);
    }
}
