<?php

namespace Tests\Unit\Setlist\Application\Query\Song\Handler;

use Setlist\Application\DataTransformer\SongDataTransformer;
use Setlist\Application\Persistence\Song\PersistedSong;
use Setlist\Application\Persistence\Song\PersistedSongCollection;
use Setlist\Application\Persistence\Song\SongRepository;
use PHPUnit\Framework\TestCase;
use Setlist\Application\Query\Song\GetSongs;
use Setlist\Application\Query\Song\Handler\GetSongsHandler;

class GetSongsHandlerTest extends TestCase
{
    private $getSongHandler;
    private $applicationSongRepository;
    private $songDataTransformer;

    protected function setUp()
    {
        $this->applicationSongRepository = $this->getMockBuilder(SongRepository::class)->getMock();
        $this->songDataTransformer = $this->getMockBuilder(SongDataTransformer::class)->getMock();
        $this->getSongHandler = new GetSongsHandler($this->applicationSongRepository, $this->songDataTransformer);
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
        $query = new GetSongs($payload);

        $song = $this->getMockBuilder(PersistedSong::class)->disableOriginalConstructor()->getMock();
        $songCollection = PersistedSongCollection::create($song);
        $result = [];
        $this->applicationSongRepository
            ->expects($this->once())
            ->method('getAllSongs')
            ->with($payload['start'], $payload['length'])
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
}
