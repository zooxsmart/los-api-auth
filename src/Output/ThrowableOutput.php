<?php

declare(strict_types=1);

namespace Los\ApiAuth\Output;

use Los\ApiAuth\Exception\NoAuthDataFound;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Throwable;

final class ThrowableOutput implements Output
{
    public function handleStrategyError(ServerRequestInterface $request, Throwable $ex): ResponseInterface
    {
        throw $ex;
    }

    public function handleAuthNotFound(ServerRequestInterface $request): ResponseInterface
    {
        throw new NoAuthDataFound('No authentication data found', 401);
    }

    public function handleAuthenticatorError(ServerRequestInterface $request, Throwable $ex): ResponseInterface
    {
        throw $ex;
    }
}
