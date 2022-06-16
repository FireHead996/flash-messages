# Flash Messages

This repository contains a Flash messages service provider. This enables you to define transient messages that persist only from the current request to the next request.

## Install

Via Composer

``` bash
$ composer require firehead996/flash-messages
```

## Usage

### Slim 4

This example assumes that you have `php-di/php-di` installed.

```php
<?php

use DI\ContainerBuilder;
use Slim\Factory\AppFactory;
use Slim\Flash\Messages;
use Slim\Routing\RouteContext;

require_once __DIR__ . '/../vendor/autoload.php';

$containerBuilder = new ContainerBuilder();

// Add container definition for the flash component
$containerBuilder->addDefinitions(
    [
        'flash' => function () {
            $storage = [];
            return new Messages($storage);
        }
    ]
);

AppFactory::setContainer($containerBuilder->build());

$app = AppFactory::create();

// Add session start middleware
$app->add(function ($request, $next) {
    // Start PHP session
    if (session_status() !== PHP_SESSION_ACTIVE) {
        session_start();
    }

    // Change flash message storage
    $this->get('flash')->__construct($_SESSION);

    return $next->handle($request);
});

$app->addErrorMiddleware(true, true, true);

// Add routes
$app->get('/', function ($request, $response) {
    // Set flash message for next request
    $this->get('flash')->addMessage('Test', 'This is a message');

    // Redirect
    $url = RouteContext::fromRequest($request)->getRouteParser()->urlFor('bar');

    return $response->withStatus(302)->withHeader('Location', $url);
});

$app->get('/bar', function ($request, $response) {
    $flash = $this->get('flash');

    // Get flash messages from previous request
    $messages = $flash->getMessages();
    print_r($messages);

    // Get the first message from a specific key
    $test = $flash->getFirstMessage('Test');
    print_r($test);

    return $response;
})->setName('bar');

$app->run();
```

> Please note that a message could be a string, object or array. Please check what your storage can handle.


## Testing

``` bash
$ phpunit
```

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
