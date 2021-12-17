<?php

namespace Drupal\route_builder\Routing;

use Symfony\Component\Routing\Route as SymfonyRoute;

class Route extends SymfonyRoute
{
    public static function create(string $path): self
    {
        /** @phpstan-ignore-next-line */
        return new static($path);
    }

    public static function get(string $path): self
    {
        return static::create($path)->setMethods((array) 'GET');
    }

    public static function put(string $path): self
    {
        return static::create($path)->setMethods((array) 'PUT');
    }

    public static function patch(string $path): self
    {
        return static::create($path)->setMethods((array) 'PATCH');
    }

    public static function post(string $path): self
    {
        return static::create($path)->setMethods((array) 'POST');
    }

    public static function delete(string $path): self
    {
        return static::create($path)->setMethods((array) 'DELETE');
    }
}
