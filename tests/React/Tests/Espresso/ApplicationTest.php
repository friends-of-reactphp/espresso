<?php

namespace React\Tests\Espresso;

use React\Espresso\Application;
use React\Http\Request;
use React\Http\Response;

class ApplicationTest extends \PHPUnit_Framework_TestCase
{
    public function testApplicationWithGetRequest()
    {
        $app = new Application();

        $app->get('/', function (\Symfony\Component\HttpFoundation\Request $request) {
            return new \Symfony\Component\HttpFoundation\Response('Hello World');
        });

        $conn = $this->getMock('React\Socket\ConnectionInterface');
        $conn
            ->expects($this->atLeastOnce())
            ->method('write')
            ->with($this->isType('string'));

        $request = new Request('GET', '/');
        $response = new Response($conn);

        $app($request, $response);
    }
}
