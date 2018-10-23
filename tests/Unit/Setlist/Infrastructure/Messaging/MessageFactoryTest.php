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
    public function factoryCanMakeInstances()
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
    public function unknownClassThrowsException()
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
}
