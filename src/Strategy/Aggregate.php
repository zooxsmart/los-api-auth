<?php

declare(strict_types=1);

namespace Los\ApiAuth\Strategy;

use Los\ApiAuth\AuthData;
use Psr\Http\Message\ServerRequestInterface;

final class Aggregate implements Strategy
{
    /** @param Strategy[] $strategies */
    public function __construct(private array $strategies)
    {
    }

    public function __invoke(ServerRequestInterface $request): AuthData|null
    {
        foreach ($this->strategies as $strategy) {
            $authData = $strategy($request);
            if (! empty($authData)) {
                return $authData;
            }
        }

        return null;
    }
}
