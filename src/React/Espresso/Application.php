<?php

namespace React\Espresso;

use React\Http\Request;
use React\Http\Response;
use Silex\Application as BaseApplication;
use Symfony\Component\HttpFoundation\Request as SymfonyRequest;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\HttpKernelInterface;

class Application extends BaseApplication
{

    /**
     * @param  Request    $request
     * @param  Response   $response
     * @throws \Exception
     */
    public function __invoke(Request $request, Response $response)
    {
        try {
            $sfRequest = $this->buildSymfonyRequest($request, $response);
            $r = $this->handle($sfRequest, HttpKernelInterface::MASTER_REQUEST, false);
            /** @var \Symfony\Component\HttpFoundation\Response $r */
            $response->writeHead($r->getStatusCode(), $r->headers->all());
            $response->end($r->getContent());
        } catch (\Exception $e) {
            $response->writeHead($e instanceof HttpException ? $e->getStatusCode() : 500);
            $response->end($e->getMessage());
        }
    }

    /**
     * @param  Request        $request
     * @param  Response       $response
     * @return SymfonyRequest
     */
    private function buildSymfonyRequest(Request $request, Response $response)
    {
        $sfRequest = SymfonyRequest::create($request->getPath(), $request->getMethod(), $request->getQuery());
        $sfRequest->attributes->set('react.espresso.request', $request);
        $sfRequest->attributes->set('react.espresso.response', $response);

        return $sfRequest;
    }
}
