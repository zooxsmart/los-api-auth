<?php

declare(strict_types=1);

namespace Los\ApiAuth\Test;

use Los\ApiAuth\ApiAuth;
use Los\ApiAuth\ApiAuthFactory;
use Los\ApiAuth\Authenticator\Authenticator;
use Los\ApiAuth\Output\Output;
use Los\ApiAuth\Strategy\Strategy;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;

/** @covers \Los\ApiAuth\ApiAuthFactory */
class ApiAuthFactoryTest extends TestCase
{
    public function testInvoke(): void
    {
        $container = $this->createStub(ContainerInterface::class);
        $container->method('get')->will($this->returnValueMap([
            [Strategy::class, $this->createStub(Strategy::class)],
            [Authenticator::class, $this->createStub(Authenticator::class)],
            [Output::class, $this->createStub(Output::class)],
            ['config', []],
        ]));

        $apiAuth = (new ApiAuthFactory())($container);
        $this->assertInstanceOf(ApiAuth::class, $apiAuth);
    }
}
