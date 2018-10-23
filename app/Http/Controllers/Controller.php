<?php

namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;
use Setlist\Application\Command\BaseCommand;
use Setlist\Application\Query\Query;
use Setlist\Infrastructure\Exception\InvalidCommandException;
use Setlist\Infrastructure\Exception\InvalidQueryException;
use Setlist\Infrastructure\Messaging\CommandBus;
use Setlist\Infrastructure\Messaging\MessageFactory;
use Setlist\Infrastructure\Messaging\QueryBus;

class Controller extends BaseController
{
    protected $messageFactory;
    private $commandBus;
    private $queryBus;

    const GENERIC_ERROR_MESSAGE = 'Something went wrong! :(';

    public function __construct(MessageFactory $messageFactory, CommandBus $commandBus, QueryBus $queryBus)
    {
        $this->messageFactory = $messageFactory;
        $this->commandBus = $commandBus;
        $this->queryBus = $queryBus;
    }

    protected function getCommand($payload, string $className): BaseCommand
    {
        try {
            $command = $this->messageFactory->makeCommand($className, $payload());
        } catch (InvalidCommandException $e) {
            return $this->returnResponse(
                ["Error" => $e->getMessage() ?: self::GENERIC_ERROR_MESSAGE],
                500
            );
        }

        return $command;
    }

    protected function getQuery($payload, string $className): Query
    {
        try {
            $query = $this->messageFactory->makeQuery($className, $payload());
        } catch (InvalidQueryException $e) {
            return $this->returnResponse(
                ["Error" => $e->getMessage() ?: self::GENERIC_ERROR_MESSAGE],
                500
            );
        }

        return $query;
    }

    protected function dispatchCommand(BaseCommand $command, string $message)
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

    protected function dispatchQuery(Query $query)
    {
        $code = 200;

        try {
            $result = $this->queryBus->handle($query);
        } catch (\Exception $e) {
            $code = $e->getCode() != 0 ? $e->getCode() : 500;
            $result = ["Error" => $e->getMessage() ?: self::GENERIC_ERROR_MESSAGE];
        }

        return $this->returnResponse($result, $code);
    }

    protected function returnResponse(array $result, int $code)
    {
        return response()->json($result, $code);
    }
}
