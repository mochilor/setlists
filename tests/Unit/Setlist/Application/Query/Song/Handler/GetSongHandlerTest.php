<?php

namespace Tests\Unit\Setlist\Application\Query\Song\Handler;

use Setlist\Application\DataTransformer\SongDataTransformer;
use Setlist\Application\Persistence\Song\PersistedSong;
use Setlist\Application\Persistence\Song\PersistedSongRepository;
use Setlist\Application\Query\Song\GetSong;
use Setlist\Application\Query\Song\Handler\GetSongHandler;
use PHPUnit\Framework\TestCase;
use Setlist\Domain\Entity\Song\Song;
use Setlist\Domain\Value\Uuid;

class GetSongHandlerTest extends TestCase
{
    private $getSongHandler;
    private $songRepository;
    private $songDataTransformer;

    protected function setUp()
    {
        $this->songRepository = $this->getMockBuilder(PersistedSongRepository::class)->getMock();
        $this->songDataTransformer = $this->getMockBuilder(SongDataTransformer::class)->getMock();
        $this->getSongHandler = new GetSongHandler($this->songRepository, $this->songDataTransformer);
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
        $query = new GetSong($payload);

        $song = $this->getMockBuilder(PersistedSong::class)->disableOriginalConstructor()->getMock();
        $result = [];
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
        $uuid = Uuid::random()->uuid();
        $payload = [
            'uuid' => $uuid,
        ];
        $query = new GetSong($payload);
        $this->songRepository
            ->expects($this->once())
            ->method('getOneSongById')
            ->with($uuid)
            ->willReturn(null);

        ($this->getSongHandler)($query);
    }
}
