<?php

declare(strict_types=1);

namespace ApiAuth\Strategy;

final class XApiKeyHeader extends CustomHeader
{
    public function __construct(bool $required = true)
    {
        parent::__construct('x-api-key', $required);
    }
}
