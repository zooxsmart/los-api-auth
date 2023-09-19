<?php

declare(strict_types=1);

namespace Los\ApiAuth\Test\Authenticator;

use Los\ApiAuth\AuthData;
use Los\ApiAuth\Authenticator\ArrayAuthenticator;
use PHPUnit\Framework\TestCase;

/** @covers \ApiAuth\Authenticator\ArrayAuthenticator */
class ArrayAuthenticatorTest extends TestCase
{
    private string $hash = '$2y$12$a3WsDmzuMD1reX0GehRVkuPwU8Labd/LXU6Ww57UJEuKu1D6MQ2MK';

    public function testValueNotFound(): void
    {
        $authData = new AuthData('key2');
        $this->expectExceptionCode(401);
        (new ArrayAuthenticator(['key1']))($authData);
    }

    public function testValue(): void
    {
        $authData = new AuthData('key1');
        $this->assertEquals('key1', (new ArrayAuthenticator(['key1']))($authData));
    }

    public function testKeyValueNotFound(): void
    {
        $authData = new AuthData('user2', 'pass1');
        $this->expectExceptionCode(401);
        (new ArrayAuthenticator(['user1']))($authData);
    }

    public function testKeyValueNoMatch(): void
    {
        $authData = new AuthData('user1', 'pass2');
        $this->expectExceptionCode(401);
        $this->assertEquals('user1', (new ArrayAuthenticator(['user1' => 'pass1']))($authData));
    }

    public function testKeyHashNoMatch(): void
    {
        $authData = new AuthData('user1', 'pass2');
        $this->expectExceptionCode(401);
        $this->assertEquals('user1', (new ArrayAuthenticator(['user1' => $this->hash]))($authData));
    }

    public function testKeyValue(): void
    {
        $authData = new AuthData('user1', 'pass1');
        $this->assertEquals('user1', (new ArrayAuthenticator(['user1' => 'pass1']))($authData));
    }

    public function testKeyHash(): void
    {
        $authData = new AuthData('user1', 'pass1');
        $this->assertEquals('user1', (new ArrayAuthenticator(['user1' => $this->hash]))($authData));
    }
}
