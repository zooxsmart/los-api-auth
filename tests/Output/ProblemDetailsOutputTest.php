<?php

declare(strict_types=1);

namespace ApiAuth\Test\Output;

use ApiAuth\Output\ProblemDetailsOutput;
use Exception;
use Laminas\Diactoros\ServerRequest;
use Mezzio\ProblemDetails\ProblemDetailsResponseFactory;
use PHPUnit\Framework\TestCase;

/**
 * @covers \ApiAuth\Output\ProblemDetailsOutput
 */
class ProblemDetailsOutputTest extends TestCase
{
    public function testHandleAuthenticatorError(): void
    {
        $problemFactory = $this->createMock(ProblemDetailsResponseFactory::class);
        $problemFactory->expects($this->once())->method('createResponseFromThrowable');
        $output = new ProblemDetailsOutput($problemFactory);
        $output->handleAuthenticatorError(new ServerRequest(), new Exception('test 1'));
    }

    public function testHandleAuthNotFound(): void
    {
        $problemFactory = $this->createMock(ProblemDetailsResponseFactory::class);
        $problemFactory->expects($this->once())->method('createResponse');
        $output = new ProblemDetailsOutput($problemFactory);
        $output->handleAuthNotFound(new ServerRequest());
    }

    public function testHandleStrategyError(): void
    {
        $problemFactory = $this->createMock(ProblemDetailsResponseFactory::class);
        $problemFactory->expects($this->once())->method('createResponseFromThrowable');
        $output = new ProblemDetailsOutput($problemFactory);
        $output->handleStrategyError(new ServerRequest(), new Exception('test 1'));
    }
}
