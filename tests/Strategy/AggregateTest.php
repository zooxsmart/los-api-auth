<?php

declare(strict_types=1);

namespace Los\ApiAuth\Test\Strategy;

use Laminas\Diactoros\ServerRequest;
use Los\ApiAuth\Strategy\Aggregate;
use Los\ApiAuth\Strategy\CustomHeader;
use Los\ApiAuth\Strategy\XApiKeyHeader;
use PHPUnit\Framework\TestCase;

/** @covers \ApiAuth\Strategy\Aggregate */
class AggregateTest extends TestCase
{
    public function testNotFound(): void
    {
        $aggregate = new Aggregate([new XApiKeyHeader(false), new CustomHeader('X-Custom-Key', true)]);
        $request   = new ServerRequest();
        $this->expectExceptionCode(401);
        ($aggregate)($request);
    }

    public function testNotFoundNotRequired(): void
    {
        $aggregate = new Aggregate([new XApiKeyHeader(false), new CustomHeader('X-Custom-Key', false)]);
        $request   = new ServerRequest();
        $this->assertNull(($aggregate)($request));
    }

    public function testFound(): void
    {
        $aggregate = new Aggregate([new XApiKeyHeader(), new CustomHeader('X-Custom-Key', false)]);
        $request   = (new ServerRequest())->withHeader('X-Api-Key', '123');
        $authData  = ($aggregate)($request);
        $this->assertNotNull($authData);
        $this->assertEquals('123', $authData->identity());
    }
}
