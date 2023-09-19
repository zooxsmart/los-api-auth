<?php

declare(strict_types=1);

namespace Los\ApiAuth\Test\Output;

use Exception;
use Laminas\Diactoros\ServerRequest;
use Los\ApiAuth\Output\ThrowableOutput;
use PHPUnit\Framework\TestCase;

/** @covers \ApiAuth\Output\ThrowableOutput */
class ThrowableOutputTest extends TestCase
{
    public function testHandleAuthenticatorError(): void
    {
        $output = new ThrowableOutput();
        $this->expectExceptionMessage('test 1');
        $output->handleAuthenticatorError(new ServerRequest(), new Exception('test 1'));
    }

    public function testHandleAuthNotFound(): void
    {
        $output = new ThrowableOutput();
        $this->expectExceptionMessage('No authentication data found');
        $output->handleAuthNotFound(new ServerRequest());
    }

    public function testHandleStrategyError(): void
    {
        $output = new ThrowableOutput();
        $this->expectExceptionMessage('test 1');
        $output->handleStrategyError(new ServerRequest(), new Exception('test 1'));
    }
}
