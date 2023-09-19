<?php

declare(strict_types=1);

namespace ApiAuth\Test\Strategy;

use ApiAuth\Strategy\Aggregate;
use ApiAuth\Strategy\CustomHeader;
use ApiAuth\Strategy\XApiKeyHeader;
use Laminas\Diactoros\ServerRequest;
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
