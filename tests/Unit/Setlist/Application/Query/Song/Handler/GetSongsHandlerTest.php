<?php

namespace Tests\Unit\Setlist\Application\Query\Song\Handler;

use Setlist\Application\DataTransformer\SongDataTransformer;
use Setlist\Application\Persistence\Setlist\PersistedSetlist;
use Setlist\Application\Persistence\Setlist\PersistedSetlistRepository;
use Setlist\Application\Persistence\Song\PersistedSong;
use Setlist\Application\Persistence\Song\PersistedSongCollection;
use Setlist\Application\Persistence\Song\PersistedSongRepository;
use PHPUnit\Framework\TestCase;
use Setlist\Application\Query\Song\GetSongs;
use Setlist\Application\Query\Song\Handler\GetSongsHandler;
use Setlist\Domain\Value\Uuid;
use Setlist\Domain\Value\UuidGenerator;

class GetSongsHandlerTest extends TestCase
{
    private $getSongHandler;
    private $applicationSongRepository;
    private $songDataTransformer;
    private $uuidGenerator;
    private $persistedSetlistRepository;

    protected function setUp()
    {
        $this->applicationSongRepository = $this->getMockBuilder(PersistedSongRepository::class)->getMock();
        $this->songDataTransformer = $this->getMockBuilder(SongDataTransformer::class)->getMock();
        $this->uuidGenerator = $this->getMockBuilder(UuidGenerator::class)->getMock();
        $this->persistedSetlistRepository = $this->getMockBuilder(PersistedSetlistRepository::class)->getMock();
        $this->getSongHandler = new GetSongsHandler(
            $this->applicationSongRepository,
            $this->songDataTransformer,
            $this->uuidGenerator,
            $this->persistedSetlistRepository
        );
    }

    /**
     * @test
     */
    public function queryHandlerCanBeInvoked()
    {
        $payload = [
            'start' => '1',
            'length' => '9',
            'title' => 'Song Title',
            'notIn' => '',
        ];
        $query = new GetSongs($payload);

        $song = $this->getMockBuilder(PersistedSong::class)->disableOriginalConstructor()->getMock();
        $songCollection = PersistedSongCollection::create($song);
        $result = [];
        $this->applicationSongRepository
            ->expects($this->once())
            ->method('getAllSongs')
            ->with($payload['start'], $payload['length'], $payload['title'])
            ->willReturn($songCollection);
        $this->songDataTransformer
            ->expects($this->exactly(count($songCollection)))
            ->method('write')
            ->with($song);
        $this->songDataTransformer
            ->expects($this->exactly(count($songCollection)))
            ->method('read')
            ->willReturn($result);

        ($this->getSongHandler)($query);
    }

    /**
     * @test
     */
    public function queryHandlerCanBeInvokedWithValidSetlistUuid()
    {
        $uuid = '8ffd680a-ff57-41f3-ac5e-bf1d877f6950';
        $payload = [
            'start' => '1',
            'length' => '9',
            'title' => 'Song Title',
            'notIn' => $uuid,
        ];
        $uuidObject = $this->getMockBuilder(Uuid::class)->getMock();
        $uuidObject->expects($this->once())
            ->method('value')
            ->willReturn($uuid);
        $persistedSetlist = $this->getMockBuilder(PersistedSetlist::class)->disableOriginalConstructor()->getMock();

        $query = new GetSongs($payload);

        $song = $this->getMockBuilder(PersistedSong::class)->disableOriginalConstructor()->getMock();
        $songCollection = PersistedSongCollection::create($song);
        $result = [];

        $this->uuidGenerator
            ->expects($this->once())
            ->method('fromString')
            ->with($uuid)
            ->willReturn($uuidObject);
        $this->persistedSetlistRepository
            ->expects($this->once())
            ->method('getOneSetlistById')
            ->with($uuid)
            ->willReturn($persistedSetlist);
        $this->applicationSongRepository
            ->expects($this->once())
            ->method('getAllSongs')
            ->with($payload['start'], $payload['length'], $payload['title'])
            ->willReturn($songCollection);
        $this->songDataTransformer
            ->expects($this->exactly(count($songCollection)))
            ->method('write')
            ->with($song);
        $this->songDataTransformer
            ->expects($this->exactly(count($songCollection)))
            ->method('read')
            ->willReturn($result);

        ($this->getSongHandler)($query);
    }

    /**
     * @test
     * @expectedException \Setlist\Application\Exception\SetlistDoesNotExistException
     */
    public function queryHandlerCanBeInvokedWithSetlistUuidAndThrowsException()
    {
        $uuid = '8ffd680a-ff57-41f3-ac5e-bf1d877f6950';
        $payload = [
            'start' => '1',
            'length' => '9',
            'title' => 'Song Title',
            'notIn' => $uuid,
        ];
        $uuidObject = $this->getMockBuilder(Uuid::class)->getMock();
        $uuidObject->expects($this->once())
            ->method('value')
            ->willReturn($uuid);

        $query = new GetSongs($payload);

        $this->uuidGenerator
            ->expects($this->once())
            ->method('fromString')
            ->with($uuid)
            ->willReturn($uuidObject);
        $this->persistedSetlistRepository
            ->expects($this->once())
            ->method('getOneSetlistById')
            ->with($uuid)
            ->willReturn(null);

        ($this->getSongHandler)($query);
    }
}
