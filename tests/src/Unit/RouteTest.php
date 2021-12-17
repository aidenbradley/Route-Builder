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

    /** @test */
    public function controller(): void
    {
        $route = Route::get('/');

        $this->assertEmpty($route->getDefault('_controller'));

        $controller = '\Drupal\controller\Controller';

        $route = Route::get('/')->controller($controller);

        $this->assertEquals($controller, $route->getDefault('_controller'));
    }

    /** @test */
    public function form(): void
    {
        $route = Route::get('/');

        $this->assertEmpty($route->getDefault('_form'));

        $route = Route::get('/')->form('my_form');

        $this->assertEquals('my_form', $route->getDefault('_form'));
    }

    /** @test */
    public function entity_view(): void
    {
        $route = Route::get('/');

        $this->assertEmpty($route->getDefault('_entity_view'));

        $route = Route::get('/')->entityView('entity_view');

        $this->assertEquals('entity_view', $route->getDefault('_entity_view'));
    }

    /** @test */
    public function entity_list(): void
    {
        $route = Route::get('/');

        $this->assertEmpty($route->getDefault('_entity_list'));

        $route = Route::get('/')->entityList('entity_list');

        $this->assertEquals('entity_list', $route->getDefault('_entity_list'));
    }

    /** @test */
    public function entity_form(): void
    {
        $route = Route::get('/');

        $this->assertEmpty($route->getDefault('_entity_form'));

        $route = Route::get('/')->entityForm('entity_form');

        $this->assertEquals('entity_form', $route->getDefault('_entity_form'));
    }

    /** @test */
    public function title(): void
    {
        $route = Route::get('/');

        $this->assertEmpty($route->getDefault('_title'));

        $route = Route::get('/')->title('my_title');

        $this->assertEquals('my_title', $route->getDefault('_title'));
    }

    /** @test */
    public function title_callback(): void
    {
        $route = Route::get('/');

        $this->assertEmpty($route->getDefault('_title_callback'));

        $titleCallback = get_class($this) . '::getTitle';

        $route = Route::get('/')->titleCallback($titleCallback);

        $this->assertEquals($titleCallback, $route->getDefault('_title_callback'));
    }

    /** @test */
    public function no_cache(): void
    {
        $route = Route::get('/');

        $this->assertFalse((bool) $route->getOption('no_cache'));

        $route->noCache();

        $this->assertTrue((bool) $route->getOption('no_cache'));
    }

    /** @test */
    public function default_access()
    {
        $route = Route::get('/');

        $this->assertFalse((bool) $route->getRequirement('_access'));

        $route->defaultAccess();

        $this->assertTrue((bool) $route->getRequirement('_access'));
    }

    /** @test */
    public function access_callback()
    {
        $route = Route::get('/');

        $this->assertEmpty($route->getRequirement('_custom_access'));

        $accessCallback = get_class($this) . '::accessCallback';

        $route->accessCallback($accessCallback);

        $this->assertEquals($accessCallback, $route->getRequirement('_custom_access'));
    }
}
