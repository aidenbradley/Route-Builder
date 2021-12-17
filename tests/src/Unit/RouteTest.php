<?php

namespace Drupal\Tests\route_builder\Unit;

use Drupal\route_builder\Routing\Route;
use Drupal\Tests\UnitTestCase;

class RouteTest extends UnitTestCase
{
    /** @test */
    public function get(): void
    {
        $route = Route::get('/');

        $this->assertTrue(in_array('GET', $route->getMethods()));
    }

    /** @test */
    public function post(): void
    {
        $route = Route::post('/');

        $this->assertTrue(in_array('POST', $route->getMethods()));
    }

    /** @test */
    public function patch(): void
    {
        $route = Route::patch('/');

        $this->assertTrue(in_array('PATCH', $route->getMethods()));
    }

    /** @test */
    public function put(): void
    {
        $route = Route::put('/');

        $this->assertTrue(in_array('PUT', $route->getMethods()));
    }

    /** @test */
    public function delete(): void
    {
        $route = Route::delete('/');

        $this->assertTrue(in_array('DELETE', $route->getMethods()));
    }
}
