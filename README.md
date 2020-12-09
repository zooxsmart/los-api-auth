# Api Auth

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
