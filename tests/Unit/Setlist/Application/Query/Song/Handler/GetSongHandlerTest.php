<?php

namespace Tests\Unit\Setlist\Application\Query\Song\Handler;

use PHPUnit\Framework\MockObject\MockObject;
use Setlist\Application\DataTransformer\SongDataTransformer;
use Setlist\Application\Persistence\Song\PersistedSong;
use Setlist\Application\Persistence\Song\PersistedSongRepository;
use Setlist\Application\Query\Song\GetSong;
use Setlist\Application\Query\Song\Handler\GetSongHandler;
use PHPUnit\Framework\TestCase;
use Setlist\Domain\Value\Uuid;
use Setlist\Domain\Value\UuidGenerator;

class GetSongHandlerTest extends TestCase
{
    private $getSongHandler;
    private $songRepository;
    private $songDataTransformer;
    private $uuidGenerator;

    const UUID_VALUE = '8ffd680a-ff57-41f3-ac5e-bf1d877f6950';

    protected function setUp()
    {
        $this->songRepository = $this->getMockBuilder(PersistedSongRepository::class)->getMock();
        $this->songDataTransformer = $this->getMockBuilder(SongDataTransformer::class)->getMock();
        $this->uuidGenerator = $this->getMockBuilder(UuidGenerator::class)->getMock();
        $this->getSongHandler = new GetSongHandler(
            $this->songRepository,
            $this->songDataTransformer,
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
        $query = new GetSong($payload);

        $song = $this->getMockBuilder(PersistedSong::class)->disableOriginalConstructor()->getMock();
        $result = [];

        $this->uuidGenerator
            ->expects($this->once())
            ->method('fromString')
            ->with($uuid)
            ->willReturn($uuidObject);
        $this->songRepository
            ->expects($this->once())
            ->method('getOneSongById')
            ->with($uuid)
            ->willReturn($song);
        $this->songDataTransformer
            ->expects($this->once())
            ->method('write')
            ->with($song);
        $this->songDataTransformer
            ->expects($this->once())
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
        $uuidObject = $this->getUuidObject($uuid);
        $query = new GetSong($payload);

        $this->uuidGenerator
            ->expects($this->once())
            ->method('fromString')
            ->with($uuid)
            ->willReturn($uuidObject);
        $this->songRepository
            ->expects($this->once())
            ->method('getOneSongById')
            ->with($uuid)
            ->willReturn(null);

        ($this->getSongHandler)($query);
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
