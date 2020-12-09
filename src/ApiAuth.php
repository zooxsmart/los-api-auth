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

use function array_search;

final class ApiAuth implements MiddlewareInterface
{
    private Strategy $strategy;
    private Authenticator $authenticator;
    private Output $output;
    /** @var string[] */
    private array $ignorePaths;

    /**
     * @param string[] $ignorePaths
     */
    public function __construct(Strategy $strategy, Authenticator $authenticator, Output $output, array $ignorePaths = [])
    {
        $this->strategy      = $strategy;
        $this->authenticator = $authenticator;
        $this->output        = $output;
        $this->ignorePaths   = $ignorePaths;
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
