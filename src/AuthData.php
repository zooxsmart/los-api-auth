<?php

declare(strict_types=1);

namespace Los\ApiAuth;

class AuthData
{
    public function __construct(private string $identity, private string|null $credential = null)
    {
    }

    public function identity(): string
    {
        return $this->identity;
    }

    public function credential(): string|null
    {
        return $this->credential;
    }
}
