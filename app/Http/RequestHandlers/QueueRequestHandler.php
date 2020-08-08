<?php

declare(strict_types=1);

namespace App\Http\RequestHandlers;

use DI\FactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class QueueRequestHandler implements RequestHandlerInterface
{
    private array $middleware = [];
    private RequestHandlerInterface $fallbackHandler;
    private FactoryInterface $factory;

    public function __construct(
        array $middleware,
        RequestHandlerInterface $fallbackHandler,
        FactoryInterface $factory
    ) {
        $this->middleware = $middleware;
        $this->fallbackHandler = $fallbackHandler;
        $this->factory = $factory;
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        if (!$this->middleware) {
            return $this->fallbackHandler->handle($request);
        }

        $middleware = array_shift($this->middleware);

        return $middleware->process($request, $this);
    }
}
