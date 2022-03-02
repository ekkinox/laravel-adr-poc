# Laravel ADR PoC

> Laravel based proof of concept for applications implementing the [ADR pattern](https://github.com/pmjones/adr).

## Table of contents

- [Usage](#usage)
- [ADR implementation](#adr-implementation)
  - [Action](#action)
  - [Domain](#domain)
  - [Responder](#responder)
- [Tests](#tests)

## Usage

After cloning this repository, simply run:

```shell
composer install && php artisan serve --port=8000
```

The application will then be exposing 2 endpoints:

| Route                                                                              | Description                                         |
|------------------------------------------------------------------------------------|-----------------------------------------------------|
| [http://localhost:8000/success?input=xxx](http://localhost:8000/success?input=xxx) | Example of success route (with optional parameter)  |
| [http://localhost:8000/error](http://localhost:8000/error)                         | Example of error route throwing an random exception |


## ADR implementation

You can find below [how is implemented](https://github.com/ekkinox/laravel-adr-poc/pull/1) the [ADR pattern](https://github.com/pmjones/adr) in this project.

### Action

The [actions](app/Http/Controllers) are small invokable classes responsible for one HTTP method + route:
- they inject and call the [domain logic](app/Domain), making them domain concerns agnostic
- they inject a [responder](app/Responders/ResponderInterface.php) to build a response from the domain logic payload, making them response concerns agnostic

To ease action classes creation:
```shell
php artisan make:controller TestAction --invokable
```

Notes:
- do not forget to remove the `Controller` inheritance on the generated code
- prefer constructor injection for dependencies

### Domain

Like in any Laravel application, they can be any service classes, if possible HTTP agnostic (to be able to reuse it in CLI, workers, jobs, ...)

In this project, we use a [dummy domain service](app/Domain/DomainService.php) as example.

### Responder

This project provides [2 responders](app/Responders), the [JsonResponder](app/Responders/JsonResponder.php) being used by default.

You can configure which responder to use in the dedicated [config/adr.php](config/adr.php) configuration file:

```php
// config/adr.php
<?php

use App\Responders\ContentNegotiationResponder;

return [
    'responder' => ContentNegotiationResponder::class,
];
```

Notes:
- for both responders, you can change the `APP_DEBUG` value to `true` (in the [.env](.env) file) to get traces on errors
- for the `ContentNegotiationResponder`, the supported types are `XML`, `CSV` and `JSON` as a fallback
- callable by providing an `Accept` request header containing respectively `application/xml`, `text/csv` and `application/json`
- you can configure the content negotiation priorities in [config/adr.php](config/adr.php)

## Tests

To run the project tests:
```shell
vendor/bin/phpunit
```
