<?php

declare(strict_types=1);

namespace ApiAuth\Output;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Throwable;

interface Output
{
    public function handleStrategyError(ServerRequestInterface $request, Throwable $ex): ResponseInterface;

    public function handleAuthNotFound(ServerRequestInterface $request): ResponseInterface;

    public function handleAuthenticatorError(ServerRequestInterface $request, Throwable $ex): ResponseInterface;
}
