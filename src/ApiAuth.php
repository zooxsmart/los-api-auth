<?php

declare(strict_types=1);

namespace Los\ApiAuth;

use Los\ApiAuth\Authenticator\Authenticator;
use Los\ApiAuth\Output\Output;
use Los\ApiAuth\Strategy\Strategy;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Throwable;

use function array_search;

final class ApiAuth implements MiddlewareInterface
{
    /** @param string[] $ignorePaths */
    public function __construct(
        private Strategy $strategy,
        private Authenticator $authenticator,
        private Output $output,
        private array $ignorePaths = [],
    ) {
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        if (! empty($this->ignorePaths)) {
            $path = $request->getUri()->getPath();
            if (array_search($path, $this->ignorePaths) !== false) {
                return $handler->handle($request);
            }
        }

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
