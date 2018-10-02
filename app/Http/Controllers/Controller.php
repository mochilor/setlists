<?php

namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;
use Setlist\Application\Exception\EntityAlreadyExistsException;
use Setlist\Application\Exception\EntityDoesNotExistException;
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
            $message = $e->getMessage() ?: self::GENERIC_ERROR_MESSAGE;
            $code = 500;

            while ($e instanceof \Exception) {
                if ($e instanceof EntityAlreadyExistsException) {
                    $message = $e->getMessage() ?: self::GENERIC_ERROR_MESSAGE;
                    $code = 409;
                    break;
                }

                if ($e instanceof EntityDoesNotExistException) {
                    $message = $e->getMessage() ?: self::GENERIC_ERROR_MESSAGE;
                    $code = 404;
                    break;
                }

                // Resto de excepciones de Domain o Application
                // ...

                $e = $e->getPrevious();
            }
        }

        return $this->returnResponse([$type => $message], $code);
    }

    private function returnResponse(array $result, int $code)
    {
        return response()->json($result, $code);
    }
}
