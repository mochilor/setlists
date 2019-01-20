<?php

namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;
use Setlist\Application\Command\BaseCommand;
use Setlist\Application\Exception\ApplicationException;
use Setlist\Application\Query\Query;
use Setlist\Domain\Exception\DomainException;
use Setlist\Infrastructure\Exception\InvalidCommandException;
use Setlist\Infrastructure\Exception\InvalidQueryException;
use Setlist\Infrastructure\Messaging\CommandBus;
use Setlist\Infrastructure\Messaging\MessageFactory;
use Setlist\Infrastructure\Messaging\QueryBus;

class Controller extends BaseController
{
    /**
     * @OA\Info(
     *   title="Setlists API",
     *   version="1.0",
     *     @OA\Contact(
     *         email="support@example.com",
     *         name="Support Team"
     *     ),
     * ),
     * @OA\Tag(
     *     name="Songs",
     *     description="Operations with songs. If 'AUTH' env variable is enabled, a token must be appended to the url in every request.",
     * ),
     * @OA\Tag(
     *     name="Setlists",
     *     description="Operations with setlists. If 'AUTH' env variable is enabled, a token must be appended to the url in every request.",
     * ),
     * @OA\Tag(
     *     name="Auth",
     *     description="Authentification operations. Needed if 'AUTH' is enabled.",
     * ),
     * @OA\Response(
     *     response="expired",
     *     description="Error: the provided token has expired, or is invalid or is obsolete.",
     * ),
     * @OA\Response(
     *     response="unauthorized",
     *     description="Error: unauthorized request (no token present)"
     * ),
     * @OA\Response(
     *     response="decoding_error",
     *     description="Error: an error ocurred while decoding the token.",
     * ),
     */

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
        $code = $command->successCode();

        try {
            $this->commandBus->handle($command);
        } catch (DomainException $e) {
            $type = 'Error';
            $code = $this->isValidHttpCode($e->getCode()) ? $e->getCode() : 500;
            $message = $e->getMessage() ?: self::GENERIC_ERROR_MESSAGE;
        } catch (ApplicationException $e) {
            $type = 'Error';
            $code = $this->isValidHttpCode($e->getCode()) ? $e->getCode() : 500;
            $message = $e->getMessage() ?: self::GENERIC_ERROR_MESSAGE;
        } catch (\Exception $e) {
            $type = 'Error';
            $code = (env('APP_DEBUG') && $this->isValidHttpCode($e->getCode())) ? $e->getCode() : 500;
            $message = env('APP_DEBUG') && $e->getMessage() ? $e->getMessage() : self::GENERIC_ERROR_MESSAGE;
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
        } catch (DomainException $e) {
            $code = $this->isValidHttpCode($e->getCode()) ? $e->getCode() : 500;
            $result = ["Error" => $e->getMessage() ?: self::GENERIC_ERROR_MESSAGE];
        } catch (ApplicationException $e) {
            $code = $this->isValidHttpCode($e->getCode()) ? $e->getCode() : 500;
            $result = ["Error" => $e->getMessage() ?: self::GENERIC_ERROR_MESSAGE];
        } catch (\Exception $e) {
            $code = (env('APP_DEBUG') && $this->isValidHttpCode($e->getCode())) ? $e->getCode() : 500;
            $result = ["Error" => (env('APP_DEBUG') && $e->getMessage()) ? $e->getMessage() : self::GENERIC_ERROR_MESSAGE];
        }

        return $this->returnResponse($result, $code);
    }

    protected function returnResponse(array $result, int $code)
    {
        return response()->json($result, $code);
    }

    private function isValidHttpCode($code): bool
    {
        $validCodes = [
            100,
            101,
            200,
            201,
            202,
            203,
            204,
            205,
            206,
            300,
            301,
            302,
            303,
            304,
            305,
            306,
            307,
            400,
            401,
            402,
            403,
            404,
            405,
            406,
            407,
            408,
            409,
            410,
            411,
            412,
            413,
            414,
            415,
            416,
            417,
            500,
            501,
            502,
            503,
            504,
            505
        ];

        return in_array($code, $validCodes);
    }
}
