<?php

namespace Tests\Unit\Setlist\Application\Query\Setlist;

use Setlist\Application\Query\Query;
use Setlist\Application\Query\Setlist\GetSetlist;
use PHPUnit\Framework\TestCase;

class GetSetlistTest extends TestCase
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
            GetSetlist::class,
            $query
        );
    }

    private function getQuery(): Query
    {
        return new GetSetlist(self::PAYLOAD);
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
