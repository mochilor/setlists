<?php

namespace Tests\Unit\Setlist\Application\Query\Setlist\Handler;

use Setlist\Application\DataTransformer\SetlistDataTransformer;
use Setlist\Application\Query\Setlist\GetSetlist;
use Setlist\Application\Query\Setlist\Handler\GetSetlistHandler;
use PHPUnit\Framework\TestCase;
use Setlist\Domain\Entity\Setlist\Setlist;
use Setlist\Domain\Entity\Setlist\SetlistRepository;
use Setlist\Domain\Value\Uuid;

class GetSetlistHandlerTest extends TestCase
{
    private $getSetlistHandler;
    private $setlistRepository;
    private $setlistDataTransformer;

    protected function setUp()
    {
        $this->setlistRepository = $this->getMockBuilder(SetlistRepository::class)->getMock();
        $this->setlistDataTransformer = $this->getMockBuilder(SetlistDataTransformer::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->getSetlistHandler = new GetSetlistHandler($this->setlistRepository, $this->setlistDataTransformer);
    }

    /**
     * @test
     */
    public function queryHandlerCanBeInvoked()
    {
        $uuid = Uuid::random()->uuid();
        $payload = [
            'uuid' => $uuid,
        ];
        $query = new GetSetlist($payload);

        $setlist = $this->getMockBuilder(Setlist::class)->getMock();
        $result = [];
        $this->setlistRepository
            ->expects($this->once())
            ->method('get')
            ->with($uuid)
            ->willReturn($setlist);
        $this->setlistDataTransformer
            ->expects($this->once())
            ->method('write')
            ->with($setlist);
        $this->setlistDataTransformer
            ->expects($this->once())
            ->method('read')
            ->willReturn($result);

        ($this->getSetlistHandler)($query);
    }

    /**
     * @test
     * @expectedException \Setlist\Application\Exception\SetlistDoesNotExistException
     */
    public function notFoundSetlistThrowsException()
    {
        $uuid = Uuid::random()->uuid();
        $payload = [
            'uuid' => $uuid,
        ];
        $query = new GetSetlist($payload);
        $this->setlistRepository
            ->expects($this->once())
            ->method('get')
            ->with($uuid)
            ->willReturn(null);

        ($this->getSetlistHandler)($query);
    }
}
