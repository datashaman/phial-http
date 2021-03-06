<?php

declare(strict_types=1);

namespace Datashaman\Phial\Http\Middleware;

use Datashaman\Phial\ConfigInterface;
use Datashaman\Phial\Http\Exceptions\HttpException;
use Fig\Http\Message\StatusCodeInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Log\LoggerInterface;
use Throwable;

class ExceptionMiddleware implements MiddlewareInterface, StatusCodeInterface
{
    private LoggerInterface $logger;
    private bool $debug;

    public function __construct(
        LoggerInterface $logger,
        ConfigInterface $config
    ) {
        $this->logger = $logger;
        $this->debug = $config->get('app.debug');
    }

    public function process(
        ServerRequestInterface $request,
        RequestHandlerInterface $handler
    ): ResponseInterface {
        try {
            return $handler->handle($request);
        } catch (HttpException $exception) {
            // Do nothing
        } catch (Throwable $exception) {
            $exception = HttpException::create(
                $exception->getMessage(),
                self::STATUS_INTERNAL_SERVER_ERROR,
                $exception,
                []
            );
        }

        $this->logger->error(
            $exception->getMessage(),
            [
                'exception' => $exception,
            ]
        );

        return $exception
            ->debug($this->debug)
            ->toJsonResponse();
    }
}
