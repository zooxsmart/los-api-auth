<?php

declare(strict_types=1);

namespace ApiAuth\Strategy;

use ApiAuth\AuthData;
use Psr\Http\Message\ServerRequestInterface;

final class Aggregate implements Strategy
{
    /** @var Strategy[] */
    private array $strategies;

    /** @param Strategy[] $strategies */
    public function __construct(array $strategies)
    {
        $this->strategies = $strategies;
    }

    public function __invoke(ServerRequestInterface $request): ?AuthData
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
