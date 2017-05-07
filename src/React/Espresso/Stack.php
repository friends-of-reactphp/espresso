<?php

namespace React\Espresso;

use React\EventLoop\Factory;
use React\Socket\Server as SocketServer;
use React\Http\Server as HttpServer;
use Pimple\Container;

/**
 * Stack Server
 */
class Stack extends Container
{
    /**
     * Construct Function
     * @param ControllerCollection $app
     */
    public function __construct($app)
    {
        $this['loop'] = function () {
            return Factory::create();
        };

        $this['socket'] = function ($stack) {
            return new SocketServer(1337, $stack['loop']);
        };

        $this['http'] = function ($stack) {
            return new HttpServer($stack['socket']);
        };

        $isFactory = is_object($app) && method_exists($app, '__invoke');
        $this['app'] = $isFactory ? $this->protectService($app) : $app;
    }

    /**
     * Listem Class
     * @param [type] $port
     * @param string $host
     * @return void
     */
    public function listen($port, $host = '127.0.0.1')
    {
        $this['http']->on('request', $this['app']);
        $this['loop']->run();
    }
    
    /**
     * Undocumented function
     *
     * @param [type] $callable
     * @return void
     */
    public function protectService($callable)
    {
        return function ($c) use ($callable) {
            return $callable;
        };
    }
}
