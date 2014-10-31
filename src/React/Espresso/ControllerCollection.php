<?php

namespace React\Espresso;

use React\Http\Request;
use React\Http\Response;
use Silex\ControllerCollection as BaseControllerCollection;
use Silex\Route;
use Symfony\Component\HttpKernel\Controller\ControllerResolverInterface;
use Symfony\Component\HttpFoundation\StreamedResponse as SymfonyStreamedResponse;
use Symfony\Component\HttpFoundation\Request as SymfonyRequest;

class ControllerCollection extends BaseControllerCollection
{
    /**
     * @var ControllerResolverInterface
     */
    private $resolver;

    public function __construct(Route $defaultRoute, ControllerResolverInterface $resolver)
    {
        parent::__construct($defaultRoute);

        $this->resolver = $resolver;
    }

    public function match($pattern, $to = null)
    {
        return parent::match($pattern, $this->wrapController($to));
    }

    private function wrapController($controller)
    {
        $resolver = $this->resolver;

        return function (Request $request, Response $response) use ($controller, $resolver) {

            if (!is_callable($controller)) {
                $controller = $resolver->getController(SymfonyRequest::create($request->getPath(), $request->getMethod()));
            }

            call_user_func($controller, $request, $response);

            return new SymfonyStreamedResponse();
        };
    }
}
