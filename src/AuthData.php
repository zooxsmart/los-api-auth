<?php

declare(strict_types=1);

namespace ApiAuth;

class AuthData
{
    private string $identity;
    private ?string $credential;

    public function __construct(string $identity, ?string $credential = null)
    {
        $this->identity   = $identity;
        $this->credential = $credential;
    }

    public function identity(): string
    {
        return $this->identity;
    }

    public function credential(): ?string
    {
        return $this->credential;
    }
}
