<?php

namespace Tests\Unit\Setlist\Application\Query\Setlist\Handler;

use PHPUnit\Framework\MockObject\MockObject;
use Setlist\Application\DataTransformer\SetlistDataTransformer;
use Setlist\Application\Persistence\Setlist\PersistedSetlist;
use Setlist\Application\Persistence\Setlist\PersistedSetlistRepository;
use Setlist\Application\Query\Setlist\GetSetlist;
use Setlist\Application\Query\Setlist\Handler\GetSetlistHandler;
use PHPUnit\Framework\TestCase;
use Setlist\Domain\Value\Uuid;
use Setlist\Domain\Value\UuidGenerator;

class GetSetlistHandlerTest extends TestCase
{
    private $getSetlistHandler;
    private $setlistRepository;
    private $setlistDataTransformer;
    private $uuidGenerator;

    const UUID_VALUE = '8ffd680a-ff57-41f3-ac5e-bf1d877f6950';

    protected function setUp()
    {
        $this->setlistRepository = $this->getMockBuilder(PersistedSetlistRepository::class)->getMock();
        $this->setlistDataTransformer = $this->getMockBuilder(SetlistDataTransformer::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->uuidGenerator = $this->getMockBuilder(UuidGenerator::class)->getMock();
        $this->getSetlistHandler = new GetSetlistHandler(
            $this->setlistRepository,
            $this->setlistDataTransformer,
            $this->uuidGenerator
        );
    }

    /**
     * @test
     */
    public function queryHandlerCanBeInvoked()
    {
        $uuid = self::UUID_VALUE;
        $payload = [
            'uuid' => $uuid,
        ];
        $uuidObject = $this->getUuidObject($uuid);

        $query = new GetSetlist($payload);

        $setlist = $this->getMockBuilder(PersistedSetlist::class)->disableOriginalConstructor()->getMock();
        $result = [];

        $this->uuidGenerator
            ->expects($this->once())
            ->method('fromString')
            ->with($uuid)
            ->willReturn($uuidObject);
        $this->setlistRepository
            ->expects($this->once())
            ->method('getOneSetlistById')
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
        $uuid = self::UUID_VALUE;
        $payload = [
            'uuid' => $uuid,
        ];
        $uuidObject = $this->getUuidObject($uuid);

        $query = new GetSetlist($payload);

        $this->uuidGenerator
            ->expects($this->once())
            ->method('fromString')
            ->with($uuid)
            ->willReturn($uuidObject);

        $this->setlistRepository
            ->expects($this->once())
            ->method('getOneSetlistById')
            ->with($uuid)
            ->willReturn(null);

        ($this->getSetlistHandler)($query);
    }

    private function getUuidObject(string $uuid): MockObject
    {
        $uuidObject = $this->getMockBuilder(Uuid::class)->getMock();
        $uuidObject->expects($this->once())
            ->method('value')
            ->willReturn($uuid);

        return $uuidObject;
    }
}
