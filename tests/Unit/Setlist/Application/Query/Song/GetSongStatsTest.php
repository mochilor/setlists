<?php

namespace Tests\Unit\Setlist\Application\Query\Song;

use Setlist\Application\Query\Query;
use PHPUnit\Framework\TestCase;
use Setlist\Application\Query\Song\GetSongStats;

class GetSongStatsTest extends TestCase
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
            GetSongStats::class,
            $query
        );
    }

    private function getQuery(): Query
    {
        return new GetSongStats(self::PAYLOAD);
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
