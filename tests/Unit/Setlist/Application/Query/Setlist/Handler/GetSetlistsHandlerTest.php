<?php

namespace Tests\Unit\Setlist\Application\Query\Setlist\Handler;

use Setlist\Application\DataTransformer\SetlistDataTransformer;
use Setlist\Application\Persistence\Setlist\PersistedSetlist;
use Setlist\Application\Persistence\Setlist\PersistedSetlistCollection;
use Setlist\Application\Persistence\Setlist\PersistedSetlistRepository;
use Setlist\Application\Query\Setlist\GetSetlists;
use PHPUnit\Framework\TestCase;
use Setlist\Application\Query\Setlist\Handler\GetSetlistsHandler;

class GetSetlistsHandlerTest extends TestCase
{
    private $getSetlistHandler;
    private $applicationSetlistRepository;
    private $setlistDataTransformer;

    protected function setUp()
    {
        $this->applicationSetlistRepository = $this->getMockBuilder(PersistedSetlistRepository::class)->getMock();
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

        $setlist = $this->getMockBuilder(PersistedSetlist::class)->disableOriginalConstructor()->getMock();
        $setlistCollection = PersistedSetlistCollection::create($setlist);
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
