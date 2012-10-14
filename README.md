# React/Espresso

React/Espresso is a proof-of-concept microframework that integrates Silex with
React/Http.

[![Build Status](https://secure.travis-ci.org/react-php/espresso.png?branch=master)](http://travis-ci.org/react-php/espresso)

## Install

The recommended way to install react/espresso is [through
composer](http://getcomposer.org).

```JSON
{
    "require": {
        "minimum-stability": "dev",
        "react/espresso": "0.2.*"
    }
}
```

## Example

    $app = new React\Espresso\Application();

    $app->get('/', function ($request, $response) {
        $response->writeHead(200, array('Content-Type' => 'text/plain'));
        $response->end("Hello World\n");
    });

    $app->get('/favicon.ico', function ($request, $response) {
        $response->writeHead(204);
        $response->end();
    });

    $app->get('/humans.txt', function ($request, $response) {
        $response->writeHead(200, array('Content-Type' => 'text/plain'));
        $response->end("I believe you are a humanoid robot.\n");
    });

    $stack = new React\Espresso\Stack($app);
    $stack->listen(1337);

## Tests

To run the test suite, you need PHPUnit.

    $ phpunit

## License

MIT, see LICENSE.
