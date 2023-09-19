# Api Auth

[![codecov](https://codecov.io/gh/Lansoweb/api-auth/branch/main/graph/badge.svg?token=0IIRZ0GYFN)](https://codecov.io/gh/Lansoweb/api-auth)
[![GitHub license](https://img.shields.io/github/license/Lansoweb/api-auth)](https://github.com/Lansoweb/api-auth/blob/1.0.x/LICENSE)
![GitHub Workflow Status](https://img.shields.io/github/workflow/status/Lansoweb/api-auth/PHPUnit%20tests)
![GitHub release (latest by date)](https://img.shields.io/github/v/release/Lansoweb/api-auth)
![Packagist PHP Version Support](https://img.shields.io/packagist/php-v/los/api-auth)

This library provides a PHP middleware for api authentication.

### Installation

```shell
composer require los/api-auth
```

### Usage

Using PSR-11 containers, use the provided factories and define factories for each requirement:
```php
return [
    \ApiAuth\ApiAuth::class => \ApiAuth\ApiAuthFactory::class,
    \ApiAuth\Strategy\Strategy::class => \ApiAuth\Strategy\XApiKeyHeader::class,
    \ApiAuth\Authenticator\Authenticator::class => \ApiAuth\Authenticator\ArrayAuthenticatorFactory::class,
    \ApiAuth\Output\Output::class => \ApiAuth\Output\ProblemDetailsOutputFactory::class,
];
```

Then add the middleware to you pipeline:
```php
$app->pipe(\ApiAuth\ApiAuth::class);
```

If successful, the middleware will register a new Request attribute ```ApiAuth\Authenticator\Authenticator``` with the identity found, so you can know which identity is authorized in the request.

If using [laminas](https://getlaminas.org), you can create a config/autoload/api-auth.global.php:
```php
<?php

declare(strict_types=1);

use ApiAuth\ApiAuth;
use ApiAuth\ApiAuthFactory;
use ApiAuth\Authenticator\ArrayAuthenticatorFactory;
use ApiAuth\Authenticator\Authenticator;
use ApiAuth\Output\Output;
use ApiAuth\Output\ProblemDetailsOutputFactory;
use ApiAuth\Strategy\BasicAuthorizationHeader;
use ApiAuth\Strategy\Strategy;

return [
    'dependencies' => [
        'invokables' => [
            Strategy::class => BasicAuthorizationHeader::class,
        ],
        'factories'  => [
            ApiAuth::class       => ApiAuthFactory::class,
            Authenticator::class => ArrayAuthenticatorFactory::class,
            Output::class        => ProblemDetailsOutputFactory::class,
        ],
    ],
    'api-auth'     => [
        'ignorePaths' => ['/health'], 
        'identities'  => ['707cd425-0a60-4d36-b2e8-c9fd7fc0f194' => '208bfbc5-e705-46b1-aec0-2b0e1b4156ad'],
    ],
];

```
### Strategies

Included:
* XApiKeyHeader: extracts the identity from the X-Api-Key header
* CustomHeader: extracts the identity from a custom header
* AuthorizationHeader: extracts the identity and credential from the Authorization header
* Aggregate: you can add as many strategies as you want, and it will return the first which succeeds
* Strategy interface to implement your own strategies

### Authenticator

Included:
* ArrayAuthenticator: validates the identity/credential against a simple array. The default is ```['api-auth']['identities'] ```
* Authenticator interface to implement your own, e.g. database

### Output

Included:
* ProblemDetailOutput: the json response output will be generated using the mezzio/problem-details package, which needs to be required in your composer.json
* ExceptionOutput: it will just throw the exception, and you can handle it in other middleware 
* Output interface to implement your own, e.g. HTML, XML
