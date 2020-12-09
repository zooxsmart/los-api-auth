<?php

declare(strict_types=1);

namespace ApiAuth;

use ApiAuth\Authenticator\Authenticator;
use ApiAuth\Output\Output;
use ApiAuth\Strategy\Strategy;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Throwable;

final class ApiAuth implements MiddlewareInterface
{
    private Strategy $strategy;
    private Authenticator $authenticator;
    private Output $output;

    public function __construct(Strategy $strategy, Authenticator $authenticator, Output $output)
    {
        $this->strategy      = $strategy;
        $this->authenticator = $authenticator;
        $this->output        = $output;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        try {
            $authData = ($this->strategy)($request);
        } catch (Throwable $ex) {
            return $this->output->handleStrategyError($request, $ex);
        }

        if ($authData === null) {
            return $this->output->handleAuthNotFound($request);
        }

        try {
            $authenticated = ($this->authenticator)($authData);
        } catch (Throwable $ex) {
            return $this->output->handleAuthenticatorError($request, $ex);
        }

        $request = $request->withAttribute(Authenticator::class, $authenticated);

        return $handler->handle($request);
    }
}
