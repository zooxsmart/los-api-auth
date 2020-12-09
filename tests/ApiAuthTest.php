<?php

declare(strict_types=1);

namespace ApiAuth\Test;

use ApiAuth\ApiAuth;
use ApiAuth\AuthData;
use ApiAuth\Authenticator\Authenticator;
use ApiAuth\Output\Output;
use ApiAuth\Strategy\Strategy;
use Exception;
use Laminas\Diactoros\Response;
use Laminas\Diactoros\ServerRequest;
use PHPUnit\Framework\TestCase;
use Psr\Http\Server\RequestHandlerInterface;

use function assert;
use function is_string;

/**
 * @covers \ApiAuth\ApiAuth
 */
class ApiAuthTest extends TestCase
{
    public function testHandleStrategyError(): void
    {
        $output = $this->createMock(Output::class);
        $output->expects($this->once())->method('handleStrategyError');

        $strategy = $this->createMock(Strategy::class);
        $strategy->expects($this->once())->method('__invoke')->willThrowException(new Exception());

        $authenticator = $this->createMock(Authenticator::class);

        $handler = $this->createMock(RequestHandlerInterface::class);

        $apiAuth = new ApiAuth($strategy, $authenticator, $output);

        $apiAuth->process(new ServerRequest(), $handler);
    }

    public function testHandleAuthNotFound(): void
    {
        $output = $this->createMock(Output::class);
        $output->expects($this->once())->method('handleAuthNotFound');

        $strategy = $this->createMock(Strategy::class);
        $strategy->expects($this->once())->method('__invoke')->willReturn(null);

        $authenticator = $this->createMock(Authenticator::class);

        $handler = $this->createMock(RequestHandlerInterface::class);

        $apiAuth = new ApiAuth($strategy, $authenticator, $output);

        $apiAuth->process(new ServerRequest(), $handler);
    }

    public function testHandleAuthenticatorError(): void
    {
        $output = $this->createMock(Output::class);
        $output->expects($this->once())->method('handleAuthenticatorError');

        $strategy = $this->createMock(Strategy::class);
        $strategy->expects($this->once())->method('__invoke')->willReturn(new AuthData('123'));

        $authenticator = $this->createMock(Authenticator::class);
        $authenticator->expects($this->once())->method('__invoke')->willThrowException(new Exception());

        $handler = $this->createMock(RequestHandlerInterface::class);

        $apiAuth = new ApiAuth($strategy, $authenticator, $output);

        $apiAuth->process(new ServerRequest(), $handler);
    }

    public function testSucceed(): void
    {
        $output = $this->createMock(Output::class);

        $strategy = $this->createMock(Strategy::class);
        $strategy->expects($this->once())->method('__invoke')->willReturn(new AuthData('123'));

        $authenticator = $this->createMock(Authenticator::class);
        $authenticator->expects($this->once())->method('__invoke')->willReturn('123');

        $handler = $this->createMock(RequestHandlerInterface::class);
        $handler->expects($this->once())->method('handle')->willReturnCallback(static function (ServerRequest $args) {
            $token = $args->getAttribute(Authenticator::class);
            assert(is_string($token));

            return (new Response())->withHeader('identity', $token);
        });

        $apiAuth = new ApiAuth($strategy, $authenticator, $output);

        $response = $apiAuth->process(new ServerRequest(), $handler);

        $this->assertEquals('123', $response->getHeader('identity')[0]);
    }
}
