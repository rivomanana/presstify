<?php declare(strict_types=1);

namespace tiFy\Routing\Strategy;

use League\Route\Strategy\JsonStrategy;
use League\Route\Route;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use tiFy\Contracts\Routing\Route as RouteContract;
use tiFy\Contracts\View\ViewController;
use Zend\Diactoros\Response;

class Json extends JsonStrategy
{
    /**
     * @inheritdoc
     */
    public function invokeRouteCallable(Route $route, ServerRequestInterface $request): ResponseInterface
    {
        /** @var RouteContract $route */
        $route->setCurrent();

        $controller = $route->getCallable($this->getContainer());

        $args = array_values($route->getVars());
        array_push($args, $request);
        $resolved = $controller(...$args);

        $psrResponse = new Response();
        if ($resolved instanceof ViewController) {
            $psrResponse->getBody()->write((string)$resolved);
        } elseif ($this->isJsonEncodable($resolved)){
            $body = json_encode($resolved);
            $psrResponse = $this->responseFactory->createResponse(200);
            $psrResponse->getBody()->write($body);
        }

        return $this->applyDefaultResponseHeaders($psrResponse);
    }
}