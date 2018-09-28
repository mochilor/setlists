<?php

namespace Setlist\Infrastructure\Lumen\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;
use Setlist\Infrastructure\Messaging\CommandBus;
use Setlist\Infrastructure\Messaging\MessageFactory;

class Controller extends BaseController
{
    protected $messageFactory;
    private $commandBus;

    public function __construct(MessageFactory $messageFactory, CommandBus $commandBus)
    {
        $this->messageFactory = $messageFactory;
        $this->commandBus = $commandBus;
    }

    protected function dispatchCommand($command, string $message)
    {
        try {
            $this->commandBus->handle($command);
        } catch (\Exception $e) {
            echo 'Oh, no! ' . $e->getMessage();
        }
    }
}
