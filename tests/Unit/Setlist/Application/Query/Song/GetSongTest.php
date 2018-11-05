<?php

namespace Tests\Unit\Setlist\Application\Query\Song;

use Setlist\Application\Query\Query;
use Setlist\Application\Query\Song\GetSong;
use PHPUnit\Framework\TestCase;

class GetSongTest extends TestCase
{
    const PAYLOAD = [
        'uuid' => '151c7306-604d-46b6-8099-0eb31b2b4af3',
    ];

    /**
     * @test
     */
    public function queryCanBeCreated()
    {
        $query = $this->getQuery();

        $this->assertInstanceOf(
            GetSong::class,
            $query
        );
    }

    private function getQuery(): Query
    {
        return new GetSong(self::PAYLOAD);
    }

    /**
     * @test
     */
    public function queryHasUuid()
    {
        $query = $this->getQuery();

        $this->assertEquals(
            self::PAYLOAD['uuid'],
            $query->uuid()
        );
    }
}
