<?php

namespace Tests\Unit\Setlist\Application\Query\Setlist\Handler;

use Setlist\Application\DataTransformer\SetlistDataTransformer;
use Setlist\Application\Persistence\Setlist\SetlistRepository;
use Setlist\Application\Query\Setlist\GetSetlists;
use PHPUnit\Framework\TestCase;
use Setlist\Application\Query\Setlist\Handler\GetSetlistsHandler;
use Setlist\Domain\Entity\Setlist\Setlist;
use Setlist\Domain\Entity\Setlist\SetlistCollection;

class GetSetlistsHandlerTest extends TestCase
{
    private $getSetlistHandler;
    private $applicationSetlistRepository;
    private $setlistDataTransformer;

    protected function setUp()
    {
        $this->applicationSetlistRepository = $this->getMockBuilder(SetlistRepository::class)->getMock();
        $this->setlistDataTransformer = $this->getMockBuilder(SetlistDataTransformer::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->getSetlistHandler = new GetSetlistsHandler($this->applicationSetlistRepository, $this->setlistDataTransformer);
    }

    /**
     * @test
     */
    public function queryHandlerCanBeInvoked()
    {
        $payload = [
            'start' => '1',
            'length' => '9',
        ];
        $query = new GetSetlists($payload);

        $setlist = $this->getMockBuilder(Setlist::class)->getMock();
        $setlistCollection = SetlistCollection::create($setlist);
        $result = [];
        $this->applicationSetlistRepository
            ->expects($this->once())
            ->method('getAllSetlists')
            ->with($payload['start'], $payload['length'])
            ->willReturn($setlistCollection);

        $this->setlistDataTransformer
            ->expects($this->exactly(count($setlistCollection)))
            ->method('write')
            ->with($setlist);
        $this->setlistDataTransformer
            ->expects($this->exactly(count($setlistCollection)))
            ->method('read')
            ->willReturn($result);

        ($this->getSetlistHandler)($query);
    }
}
