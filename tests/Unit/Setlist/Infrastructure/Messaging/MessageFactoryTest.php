<?php

namespace Tests\Unit\Setlist\Infrastructure\Messaging;

use PHPUnit\Framework\TestCase;
use Setlist\Infrastructure\Messaging\MessageFactory;

class MessageFactoryTest extends TestCase
{
    private $messageFactory;

    protected function setUp()
    {
        $this->messageFactory = new MessageFactory();
    }

    /**
     * @test
     */
    public function factoryCanMakeCommandInstances()
    {
        $this->assertInstanceOf(
            DummyCommand::class,
            $this->messageFactory->makeCommand(DummyCommand::class, [])
        );
    }

    /**
     * @test
     * @expectedException \Setlist\Infrastructure\Exception\InvalidCommandException
     */
    public function unknownCommandClassThrowsException()
    {
        $this->messageFactory->makeCommand('UnknownClassName', []);
    }

    /**
     * @test
     * @expectedException \Setlist\Infrastructure\Exception\InvalidCommandException
     */
    public function nonBaseCommandClassThrowsException()
    {
        $this->messageFactory->makeCommand(MessageFactory::class, []);
    }

    /**
     * @test
     */
    public function factoryCanMakeQueryInstances()
    {
        $this->assertInstanceOf(
            DummyQuery::class,
            $this->messageFactory->makeQuery(DummyQuery::class, [])
        );
    }

    /**
     * @test
     * @expectedException \Setlist\Infrastructure\Exception\InvalidQueryException
     */
    public function unknownQueryClassThrowsException()
    {
        $this->messageFactory->makeQuery('UnknownClassName', []);
    }

    /**
     * @test
     * @expectedException \Setlist\Infrastructure\Exception\InvalidQueryException
     */
    public function nonQueryClassThrowsException()
    {
        $this->messageFactory->makeQuery(MessageFactory::class, []);
    }
}
