# React/Espresso

React/Espresso is a proof-of-concept microframework that integrates Silex with
React/Http.

[![Build Status](https://secure.travis-ci.org/reactphp/espresso.png?branch=master)](http://travis-ci.org/reactphp/espresso)

## Install

The recommended way to install react/espresso is [through
composer](http://getcomposer.org).

```
$ composer require react/espresso
```

## Example

> all your Controllers stay the same, the converting between symfony request/response and react request/response is completely shallow

```php
<?php
// react.php

$app = new React\Espresso\Application();

$app->get('/hello/{name}', function ($name) use ($app) {
    return 'Hello '.$app->escape($name);
});

$stack = new React\Espresso\Stack($app);
$stack->listen(1337);
```

```
$ php react.php
```

> now visit  [http://localhost:1337/hello/react](http://localhost:1337/hello/react)

## Tests

To run the test suite, you need PHPUnit.

```
$ phpunit
```

## License

MIT, see LICENSE.
