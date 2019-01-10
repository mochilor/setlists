<?php

namespace Tests\Unit\Setlist\Application\Query\Setlist;

use Setlist\Application\Query\Query;
use PHPUnit\Framework\TestCase;
use Setlist\Application\Query\Setlist\GetSetlists;

class GetSetlistsTest extends TestCase
{
    const PAYLOAD = [
        'start' => '1',
        'length' => '9',
        'name' => 'Hello!',
    ];

    /**
     * @test
     */
    public function queryCanBeCreated()
    {
        $query = $this->getQuery();

        $this->assertInstanceOf(
            GetSetlists::class,
            $query
        );
    }

    private function getQuery(): Query
    {
        return new GetSetlists(self::PAYLOAD);
    }

    /**
     * @test
     */
    public function queryHasStart()
    {
        $query = $this->getQuery();

        $this->assertEquals(
            self::PAYLOAD['start'],
            $query->start()
        );
    }

    /**
     * @test
     */
    public function queryHasLength()
    {
        $query = $this->getQuery();

        $this->assertEquals(
            self::PAYLOAD['length'],
            $query->length()
        );
    }

    /**
     * @test
     */
    public function queryHasName()
    {
        $query = $this->getQuery();

        $this->assertEquals(
            self::PAYLOAD['name'],
            $query->name()
        );
    }
}
