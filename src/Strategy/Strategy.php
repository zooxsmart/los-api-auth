<?php

declare(strict_types=1);

namespace ApiAuth\Strategy;

use ApiAuth\AuthData;
use Psr\Http\Message\ServerRequestInterface;

interface Strategy
{
    public function __invoke(ServerRequestInterface $request): AuthData|null;
}
