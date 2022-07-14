<?php

declare(strict_types=1);

namespace Bambamboole\Framework\Http\Routing;

use Bambamboole\Framework\Core\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Matcher\CompiledUrlMatcher;
use Symfony\Component\Routing\Matcher\Dumper\CompiledUrlMatcherDumper;
use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\Routing\Matcher\UrlMatcherInterface;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\RouteCollection;

class Router implements RouterInterface
{
    private ?UrlMatcherInterface $urlMatcher = null;

    public function __construct(
        private readonly RouteRegistrar $routeRegistrar,
        private readonly Filesystem $filesystem,
        private readonly string $routesPath,
        private readonly string $compiledRoutesPath,
        private readonly bool $enableCache = true,
    ) {
    }

    public function getRoutesPath(): string
    {
        return $this->routesPath;
    }

    public function getCompiledRoutesPath(): string
    {
        return $this->compiledRoutesPath;
    }

    public function isCacheEnabled(): bool
    {
        return $this->enableCache;
    }

    public function loadRoutes(): void
    {
        if (!$this->urlMatcher) {
            $this->urlMatcher = $this->getUrlMatcher();
        }
    }

    public function match(Request $request): HttpMatch
    {
        $this->urlMatcher->setContext((new RequestContext())->fromRequest($request));

        return HttpMatch::fromSymfonyUrlMatcherResult(
            $this->urlMatcher->match($request->getPathInfo()),
        );
    }

    private function getRouteCollection(): RouteCollection
    {
        $routes = $this->routeRegistrar;

        require $this->routesPath;

        return $routes->getCollection();
    }

    /** @return mixed[] */
    private function getCompiledRoutes(): array
    {
        if ($this->filesystem->exists($this->compiledRoutesPath)) {
            return require $this->compiledRoutesPath;
        }
        $collection = $this->getRouteCollection();
        $compiler = new CompiledUrlMatcherDumper($collection);

        $compiledRoutesString = $compiler->dump();
        $this->filesystem->put($this->compiledRoutesPath, $compiledRoutesString);

        return require $this->compiledRoutesPath;
    }

    private function getUrlMatcher(): UrlMatcherInterface
    {
        if ($this->enableCache) {
            return new CompiledUrlMatcher(
                $this->getCompiledRoutes(),
                new RequestContext(),
            );
        }

        return new UrlMatcher(
            $this->getRouteCollection(),
            new RequestContext(),
        );
    }
}
