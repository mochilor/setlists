<?php

namespace Tests\Unit\Setlist\Application\Query\Song\Handler;

use PHPUnit\Framework\MockObject\MockObject;
use Setlist\Application\DataTransformer\SetlistDataTransformer;
use Setlist\Application\Persistence\Setlist\PersistedSetlist;
use Setlist\Application\Persistence\Setlist\PersistedSetlistCollection;
use Setlist\Application\Persistence\Setlist\PersistedSetlistRepository;
use Setlist\Application\Persistence\Song\PersistedSong;
use Setlist\Application\Persistence\Song\PersistedSongRepository;
use Setlist\Application\Query\Song\GetSongStats;
use Setlist\Application\Query\Song\Handler\GetSongStatsHandler;
use PHPUnit\Framework\TestCase;
use Setlist\Domain\Value\Uuid;
use Setlist\Domain\Value\UuidGenerator;

class GetSongStatsHandlerTest extends TestCase
{
    private $getSongHandler;
    private $persistedSongRepository;
    private $persistedSetlistRepository;
    private $setlistDataTransformer;
    private $uuidGenerator;

    const UUID_VALUE = '8ffd680a-ff57-41f3-ac5e-bf1d877f6950';

    protected function setUp()
    {
        $this->uuidGenerator = $this->getMockBuilder(UuidGenerator::class)->getMock();
        $this->persistedSongRepository = $this->getMockBuilder(PersistedSongRepository::class)->getMock();
        $this->persistedSetlistRepository = $this->getMockBuilder(PersistedSetlistRepository::class)->getMock();
        $this->setlistDataTransformer = $this->getMockBuilder(SetlistDataTransformer::class)->getMock();
        $this->getSongHandler = new GetSongStatsHandler(
            $this->uuidGenerator,
            $this->persistedSongRepository,
            $this->persistedSetlistRepository,
            $this->setlistDataTransformer
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
        $uuidObject = $this->getUuidObject($uuid, 2);
        $query = new GetSongStats($payload);

        $song = $this->getMockBuilder(PersistedSong::class)->disableOriginalConstructor()->getMock();
        $result = [];

        $setlist = $this->getMockBuilder(PersistedSetlist::class)->disableOriginalConstructor()->getMock();
        $setlistCollection = PersistedSetlistCollection::create($setlist);

        $this->uuidGenerator
            ->expects($this->once())
            ->method('fromString')
            ->with($uuid)
            ->willReturn($uuidObject);
        $this->persistedSongRepository
            ->expects($this->once())
            ->method('getOneSongById')
            ->with($uuid)
            ->willReturn($song);
        $this->persistedSetlistRepository
            ->expects($this->once())
            ->method('getSetlistsInfoBySongId')
            ->with($uuid)
            ->willReturn($setlistCollection);
        $this->setlistDataTransformer
            ->expects($this->exactly(count($setlistCollection)))
            ->method('write')
            ->with($setlist);
        $this->setlistDataTransformer
            ->expects($this->exactly(count($setlistCollection)))
            ->method('read')
            ->willReturn($result);

        ($this->getSongHandler)($query);
    }

    /**
     * @test
     * @expectedException \Setlist\Application\Exception\SongDoesNotExistException
     */
    public function notFoundSongThrowsException()
    {
        $uuid = self::UUID_VALUE;
        $payload = [
            'uuid' => $uuid,
        ];
        $uuidObject = $this->getUuidObject($uuid, 1);
        $query = new GetSongStats($payload);

        $this->uuidGenerator
            ->expects($this->once())
            ->method('fromString')
            ->with($uuid)
            ->willReturn($uuidObject);
        $this->persistedSongRepository
            ->expects($this->once())
            ->method('getOneSongById')
            ->with($uuid)
            ->willReturn(null);

        ($this->getSongHandler)($query);
    }

    private function getUuidObject(string $uuid, int $valueTimes): MockObject
    {
        $uuidObject = $this->getMockBuilder(Uuid::class)->getMock();
        $uuidObject->expects($this->exactly($valueTimes))
            ->method('value')
            ->willReturn($uuid);

        return $uuidObject;
    }
}
