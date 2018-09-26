<?php

namespace Setlist\Infrastructure\Lumen\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;
use Setlist\Infrastructure\Messaging\MessageFactory;

class Controller extends BaseController
{
    protected $messageFactory;

    public function __construct(MessageFactory $messageFactory)
    {
        $this->messageFactory = $messageFactory;
    }

    protected function dispatchCommand($command, string $string)
    {
        echo $string . "\n\n";
        die(var_dump($command));
    }
}
