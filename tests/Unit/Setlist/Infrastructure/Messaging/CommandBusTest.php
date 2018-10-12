<?php

namespace Tests\Unit\Setlist\Infrastructure\Messaging;

use Setlist\Application\Command\BaseCommand;
use Setlist\Infrastructure\Messaging\CommandBus;
use PHPUnit\Framework\TestCase;

class CommandBusTest extends TestCase
{
    private $commandBus;

    public function setUp()
    {
        $this->commandBus = new CommandBus();
    }

    /**
     * @test
     */
    public function commandBusCanHandleCommands()
    {
        $this->commandBus->addHandler(DummyCommand::class, new DummyHandler());

        $this->assertEquals(
            DummyHandler::$message,
            $this->commandBus->handle(new DummyCommand([]))
        );
    }

    /**
     * @test
     * @expectedException \Setlist\Infrastructure\Exception\InvalidCommandException
     */
    public function commandWithoutHandlerThrowsException()
    {
        $this->commandBus->handle(new DummyCommand([]));
    }
}

class DummyCommand extends BaseCommand
{
}

class DummyHandler
{
    public static $message = 'Invoked!';

    public function __invoke()
    {
        return self::$message;
    }
}
