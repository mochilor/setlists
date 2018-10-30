<?php

namespace Tests\Unit\Setlist\Infrastructure\Messaging;

use Setlist\Application\Command\BaseCommand;
use Setlist\Application\Query\Query;
use Setlist\Infrastructure\Messaging\QueryBus;
use PHPUnit\Framework\TestCase;

class QueryBusTest extends TestCase
{
    private $queryBus;

    public function setUp()
    {
        $this->queryBus = new QueryBus();
    }

    /**
     * @test
     */
    public function queryBusCanHandleQueries()
    {
        $this->queryBus->addHandler(DummyQuery::class, new DummyQueryHandler());

        $this->assertEquals(
            DummyHandler::$message,
            $this->queryBus->handle(new DummyQuery([]))
        );
    }

    /**
     * @test
     * @expectedException \Setlist\Infrastructure\Exception\InvalidQueryException
     */
    public function queryWithoutHandlerThrowsException()
    {
        $this->queryBus->handle(new DummyQuery([]));
    }
}

class DummyQuery extends Query
{
}

class DummyQueryHandler
{
    public static $message = 'Invoked!';

    public function __invoke()
    {
        return self::$message;
    }
}
