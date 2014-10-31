<?php

namespace React\Espresso;

use Silex\ControllerCollection as BaseControllerCollection;
use Silex\Route;
  use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ControllerResolverInterface;
use Symfony\Component\HttpFoundation\StreamedResponse as SymfonyStreamedResponse;

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

        return function (Request $sfRequest) use ($controller, $resolver) {
            $request = $sfRequest->attributes->get('react.espresso.request');
            $response = $sfRequest->attributes->get('react.espresso.response');

            if (!is_callable($controller)) {
                $controller = $resolver->getController($sfRequest);
            }

            call_user_func($controller, $request, $response);

            return new SymfonyStreamedResponse();
        };
    }
}
