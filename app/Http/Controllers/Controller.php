<?php

namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;
use Setlist\Infrastructure\Messaging\CommandBus;
use Setlist\Infrastructure\Messaging\MessageFactory;

class Controller extends BaseController
{
    protected $messageFactory;
    private $commandBus;

    const GENERIC_ERROR_MESSAGE = 'Something went wrong! :(';

    public function __construct(MessageFactory $messageFactory, CommandBus $commandBus)
    {
        $this->messageFactory = $messageFactory;
        $this->commandBus = $commandBus;
    }

    protected function dispatchCommand($command, string $message)
    {
        $type = 'Result';
        $code = 200;

        try {
            $this->commandBus->handle($command);
        } catch (\Exception $e) {
            $type = 'Error';
            $code = $e->getCode() != 0 ? $e->getCode() : 500;
            $message = $e->getMessage() ?: self::GENERIC_ERROR_MESSAGE;

        } catch (\Throwable $e) {
            $type = 'Error';
            $code = 500;
            $message = $e->getMessage() ?: self::GENERIC_ERROR_MESSAGE;
        }

        return $this->returnResponse([$type => $message], $code);
    }

    private function returnResponse(array $result, int $code)
    {
        return response()->json($result, $code);
    }
}
