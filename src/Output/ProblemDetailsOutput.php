<?php

declare(strict_types=1);

namespace ApiAuth\Output;

use Mezzio\ProblemDetails\ProblemDetailsResponseFactory;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Throwable;

final class ProblemDetailsOutput implements Output
{
    private ProblemDetailsResponseFactory $problemResponseFactory;

    public function __construct(ProblemDetailsResponseFactory $problemResponseFactory)
    {
        $this->problemResponseFactory = $problemResponseFactory;
    }

    public function handleStrategyError(ServerRequestInterface $request, Throwable $ex): ResponseInterface
    {
        return $this->problemResponseFactory->createResponseFromThrowable($request, $ex);
    }

    public function handleAuthNotFound(ServerRequestInterface $request): ResponseInterface
    {
        return $this->problemResponseFactory->createResponse($request, 401, 'No authentication data found');
    }

    public function handleAuthenticatorError(ServerRequestInterface $request, Throwable $ex): ResponseInterface
    {
        return $this->problemResponseFactory->createResponseFromThrowable($request, $ex);
    }
}
