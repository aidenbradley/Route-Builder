<?php

namespace Drupal\Tests\route_builder\Unit;

use Drupal\route_builder\Routing\Route;
use Drupal\Tests\UnitTestCase;
use Symfony\Component\Routing\Route as SymfonyRoute;

class RouteTest extends UnitTestCase
{
    /** @test */
    public function get(): void
    {
        $route = Route::get(__FUNCTION__, '/');

        $this->assertTrue(in_array('GET', $route->getMethods()));
    }

    /** @test */
    public function accepts_get(): void
    {
        $route = Route::post(__FUNCTION__, '/');

        $this->assertFalse(in_array('GET', $route->getMethods()));

        $route->acceptsGet();

        $this->assertTrue(in_array('GET', $route->getMethods()));
    }

    /** @test */
    public function post(): void
    {
        $route = Route::post(__FUNCTION__, '/');

        $this->assertTrue(in_array('POST', $route->getMethods()));
    }

    /** @test */
    public function accepts_post(): void
    {
        $route = Route::get(__FUNCTION__, '/');

        $this->assertFalse(in_array('POST', $route->getMethods()));

        $route->acceptsPost();

        $this->assertTrue(in_array('POST', $route->getMethods()));
    }

    /** @test */
    public function patch(): void
    {
        $route = Route::patch(__FUNCTION__, '/');

        $this->assertTrue(in_array('PATCH', $route->getMethods()));
    }

    /** @test */
    public function accepts_patch(): void
    {
        $route = Route::get(__FUNCTION__, '/');

        $this->assertFalse(in_array('PATCH', $route->getMethods()));

        $route->acceptsPatch();

        $this->assertTrue(in_array('PATCH', $route->getMethods()));
    }

    /** @test */
    public function put(): void
    {
        $route = Route::put(__FUNCTION__, '/');

        $this->assertTrue(in_array('PUT', $route->getMethods()));
    }

    /** @test */
    public function accepts_put(): void
    {
        $route = Route::get(__FUNCTION__, '/');

        $this->assertFalse(in_array('PUT', $route->getMethods()));

        $route->acceptsPut();

        $this->assertTrue(in_array('PUT', $route->getMethods()));
    }

    /** @test */
    public function delete(): void
    {
        $route = Route::delete(__FUNCTION__, '/');

        $this->assertTrue(in_array('DELETE', $route->getMethods()));
    }

    /** @test */
    public function accepts_delete(): void
    {
        $route = Route::get(__FUNCTION__, '/');

        $this->assertFalse(in_array('DELETE', $route->getMethods()));

        $route->acceptsDelete();

        $this->assertTrue(in_array('DELETE', $route->getMethods()));
    }

    /** @test */
    public function accepts_methods(): void
    {
        $route = Route::get(__FUNCTION__, '/');

        $this->assertEquals([
            'GET'
        ], $route->getMethods());

        $route->acceptsMethods([
            'PATCH',
            'PUT',
            'POST',
            'DELETE',
        ]);

        $this->assertEquals([
            'GET',
            'PATCH',
            'PUT',
            'POST',
            'DELETE',
        ], $route->getMethods());
    }

    /** @test */
    public function controller(): void
    {
        $route = Route::get(__FUNCTION__, '/');

        $this->assertEmpty($route->getDefault('_controller'));

        $controller = '\Drupal\controller\Controller';

        $route = Route::get(__FUNCTION__ . '_new_route', '/')->setController($controller, 'render');

        $this->assertEquals($controller . '::render', $route->getDefault('_controller'));

        $route->setController('\Drupal\controller\Controller');

        $this->assertEquals($controller . '::__invoke', $route->getDefault('_controller'));
    }

    /** @test */
    public function form(): void
    {
        $route = Route::get(__FUNCTION__, '/');

        $this->assertEmpty($route->getDefault('_form'));

        $route = Route::get(__FUNCTION__ . '_new_route', '/')->form('my_form');

        $this->assertEquals('my_form', $route->getDefault('_form'));
    }

    /** @test */
    public function entity_view(): void
    {
        $route = Route::get(__FUNCTION__, '/');

        $this->assertEmpty($route->getDefault('_entity_view'));

        $route = Route::get(__FUNCTION__ . '_new_route', '/')->entityView('entity_view');

        $this->assertEquals('entity_view', $route->getDefault('_entity_view'));
    }

    /** @test */
    public function entity_list(): void
    {
        $route = Route::get(__FUNCTION__, '/');

        $this->assertEmpty($route->getDefault('_entity_list'));

        $route = Route::get(__FUNCTION__ . '_new_route', '/')->entityList('entity_list');

        $this->assertEquals('entity_list', $route->getDefault('_entity_list'));
    }

    /** @test */
    public function entity_form(): void
    {
        $route = Route::get(__FUNCTION__, '/');

        $this->assertEmpty($route->getDefault('_entity_form'));

        $route = Route::get(__FUNCTION__ . '_new_route', '/')->entityForm('entity_form');

        $this->assertEquals('entity_form', $route->getDefault('_entity_form'));
    }

    /** @test */
    public function title(): void
    {
        $route = Route::get(__FUNCTION__, '/');

        $this->assertEmpty($route->getDefault('_title'));

        $route = Route::get(__FUNCTION__ . '_new_route', '/')->setTitle('my_title');

        $this->assertEquals('my_title', $route->getDefault('_title'));
    }

    /** @test */
    public function parameter_default_value(): void
    {
        $route = Route::get(__FUNCTION__, '/route/{param}');

        $this->assertEmpty($route->getDefault('param'));

        $route->defaultParamValue('param', 'value');

        $this->assertEquals('value', $route->getDefault('param'));
    }

    /** @test */
    public function title_callback(): void
    {
        $route = Route::get(__FUNCTION__, '/');

        $this->assertEmpty($route->getDefault('_title_callback'));

        $titleCallback = get_class($this) . '::getTitle';

        $route = Route::get(__FUNCTION__ . '_new_route', '/')->setTitleCallback($titleCallback);

        $this->assertEquals($titleCallback, $route->getDefault('_title_callback'));
    }

    /** @test */
    public function requires_all_permissions(): void
    {
        $permissions = [
            'first_permission',
            'second_permission',
            'third_permission',
        ];

        $route = Route::get(__FUNCTION__, '/')->requiresAllPermissions($permissions);

        $this->assertEquals(implode(',', $permissions), $route->getRequirement('_permission'));
    }

    /** @test */
    public function requires_any_permission(): void
    {
        $permissions = [
            'first_permission',
            'second_permission',
            'third_permission',
        ];

        $route = Route::get(__FUNCTION__, '/')->requiresAnyPermission($permissions);

        $this->assertEquals(implode('+', $permissions), $route->getRequirement('_permission'));
    }

    /** @test */
    public function requires_permission(): void
    {
        $route = Route::get(__FUNCTION__, '/')->requiresPermission('access content');

        $this->assertEquals('access content', $route->getRequirement('_permission'));
    }

    /** @test */
    public function requires_all_roles(): void
    {
        $roles = [
            'first_role',
            'second_role',
            'third_role',
        ];

        $route = Route::get(__FUNCTION__, '/')->requiresAllRoles($roles);

        $this->assertEquals(implode(',', $roles), $route->getRequirement('_role'));
    }

    /** @test */
    public function requires_any_role(): void
    {
        $roles = [
            'first_role',
            'second_role',
            'third_role',
        ];

        $route = Route::get(__FUNCTION__, '/')->requiresAnyRole($roles);

        $this->assertEquals(implode('+', $roles), $route->getRequirement('_role'));
    }

    /** @test */
    public function requires_role(): void
    {
        $route = Route::get(__FUNCTION__, '/')->requiresRole('editor');

        $this->assertEquals('editor', $route->getRequirement('_role'));
    }

    /** @test */
    public function default_access(): void
    {
        $route = Route::get(__FUNCTION__, '/');

        $this->assertFalse((bool)$route->getRequirement('_access'));

        $route->hasDefaultAccess();

        $this->assertTrue((bool)$route->getRequirement('_access'));
    }

    /** @test */
    public function entity_access(): void
    {
        $route = Route::get(__FUNCTION__, '/');

        $this->assertEmpty($route->getRequirement('_entity_access'));

        $route->entityAccess('node.view');

        $this->assertEquals('node.view', $route->getRequirement('_entity_access'));
    }

    /** @test */
    public function entity_validaton(): void
    {
        $route = Route::get(__FUNCTION__, '/');

        $this->assertEmpty($route->getRequirement('node'));

        $route->entityValidation('node', '\d+');

        $this->assertEquals('\d+', $route->getRequirement('node'));
    }

    /** @test */
    public function entity_bundles(): void
    {
        $route = Route::get(__FUNCTION__, '/');

        $this->assertEmpty($route->getRequirement('_entity_bundles'));

        $route->entityBundles('node', 'article');

        $this->assertEquals('node:article', $route->getRequirement('_entity_bundles'));

        $route->entityBundles('node', [
            'article',
            'page'
        ]);

        $this->assertEquals('node:article|page', $route->getRequirement('_entity_bundles'));
    }

    /** @test */
    public function entity_create_access(): void
    {
        $route = Route::get(__FUNCTION__, '/');

        $this->assertEmpty($route->getRequirement('_entity_create_access'));

        $route->entityCreateAccess('node', 'article');

        $this->assertEquals('node:article', $route->getRequirement('_entity_create_access'));

        $route->entityCreateAccess('node', '{route_parameter}');

        $this->assertEquals('node:{route_parameter}', $route->getRequirement('_entity_create_access'));
    }

    /** @test */
    public function access_callback(): void
    {
        $route = Route::get(__FUNCTION__, '/');

        $this->assertEmpty($route->getRequirement('_custom_access'));

        $accessCallback = get_class($this) . '::accessCallback';

        $route->customAccessCallback($accessCallback);

        $this->assertEquals($accessCallback, $route->getRequirement('_custom_access'));
    }

    /** @test */
    public function format(): void
    {
        $route = Route::get(__FUNCTION__, '/');

        $this->assertEmpty($route->getRequirement('_format'));

        $route->returnsFormat('json');

        $this->assertEquals('json', $route->getRequirement('_format'));
    }

    /** @test */
    public function json_format(): void
    {
        $route = Route::get(__FUNCTION__, '/');

        $this->assertEmpty($route->getRequirement('_format'));

        $route->returnsJsonFormat();

        $this->assertEquals('json', $route->getRequirement('_format'));
    }

    /** @test */
    public function html_format(): void
    {
        $route = Route::get(__FUNCTION__, '/');

        $this->assertEmpty($route->getRequirement('_format'));

        $route->returnsHtmlFormat();

        $this->assertEquals('html', $route->getRequirement('_format'));
    }

    /** @test */
    public function set_content_type_format(): void
    {
        $route = Route::get(__FUNCTION__, '/');

        $this->assertEmpty($route->getRequirement('_content_type_format'));

        $route->setContentTypeFormat('bin');

        $this->assertEquals('bin', $route->getRequirement('_content_type_format'));
    }

    /** @test */
    public function only_accepts_json(): void
    {
        $route = Route::get(__FUNCTION__, '/');

        $this->assertEmpty($route->getRequirement('_content_type_format'));

        $route->onlyAcceptsJson();

        $this->assertEquals('json', $route->getRequirement('_content_type_format'));
    }

    /** @test */
    public function onyl_accepts_xml(): void
    {
        $route = Route::get(__FUNCTION__, '/');

        $this->assertEmpty($route->getRequirement('_content_type_format'));

        $route->onlyAcceptsXml();

        $this->assertEquals('xml', $route->getRequirement('_content_type_format'));
    }

    /** @test */
    public function xml_format(): void
    {
        $route = Route::get(__FUNCTION__, '/');

        $this->assertEmpty($route->getRequirement('_format'));

        $route->returnsXmlFormat();

        $this->assertEquals('xml', $route->getRequirement('_format'));
    }

    /** @test */
    public function depends_on_all_modules(): void
    {
        $route = Route::get(__FUNCTION__, '/');

        $this->assertEmpty($route->getRequirement('_module_dependencies'));

        $modules = [
            'node',
            'media',
        ];

        $route->dependsOnAllModules($modules);

        $this->assertEquals(implode(',', $modules), $route->getRequirement('_module_dependencies'));
    }

    /** @test */
    public function depends_on_any_modules(): void
    {
        $route = Route::get(__FUNCTION__, '/');

        $this->assertEmpty($route->getRequirement('_module_dependencies'));

        $modules = [
            'node',
            'media',
        ];

        $route->dependsOnAnyModule($modules);

        $this->assertEquals(implode('+', $modules), $route->getRequirement('_module_dependencies'));
    }

    /** @test */
    public function depends_on_module(): void
    {
        $route = Route::get(__FUNCTION__, '/');

        $this->assertEmpty($route->getRequirement('_module_dependencies'));

        $route->dependsOnModule('node');

        $this->assertEquals('node', $route->getRequirement('_module_dependencies'));
    }

    /** @test */
    public function uses_csrf(): void
    {
        $route = Route::get(__FUNCTION__, '/');

        $this->assertFalse((bool)$route->getRequirement('_csrf_token'));

        $route->usesCsrf();

        $this->assertTrue((bool)$route->getRequirement('_csrf_token'));
    }

    /** @test */
    public function requires_csrf_token_header(): void
    {
        $route = Route::get(__FUNCTION__, '/');

        $this->assertFalse((bool)$route->getRequirement('_csrf_request_header_token'));

        $route->requiresCsrfTokenHeader();

        $this->assertTrue((bool)$route->getRequirement('_csrf_request_header_token'));
    }

    /** @test */
    public function access_user_register(): void
    {
        $route = Route::get(__FUNCTION__, '/');

        $this->assertFalse((bool)$route->getRequirement('_access_user_register'));

        $route->accessUserRegister();

        $this->assertTrue((bool)$route->getRequirement('_access_user_register'));
    }

    /** @test */
    public function requires_login_to_access(): void
    {
        $route = Route::get(__FUNCTION__, '/');

        $this->assertFalse((bool)$route->getRequirement('_user_is_logged_in'));

        $route->requiresLoginToAccess();

        $this->assertTrue((bool)$route->getRequirement('_user_is_logged_in'));
    }

    /** @test */
    public function is_admin_route(): void
    {
        $route = Route::get(__FUNCTION__, '/');

        $this->assertFalse((bool)$route->getOption('_admin_route'));

        $route->isAdminRoute();

        $this->assertTrue((bool)$route->getOption('_admin_route'));
    }

    /** @test */
    public function requires_authentication(): void
    {
        $route = Route::get(__FUNCTION__, '/');

        $this->assertEmpty($route->getOption('_auth'));

        $route->requiresAuthentication('basic_auth');

        $this->assertEquals(['basic_auth'], $route->getOption('_auth'));

        $route->requiresAuthentication('cookie');

        $this->assertEquals([
            'basic_auth',
            'cookie',
        ], $route->getOption('_auth'));

        $route = Route::get(__FUNCTION__ . '_new_route', '/')->requiresAuthentication([
            'basic_auth',
            'cookie',
        ]);

        $this->assertEquals([
            'basic_auth',
            'cookie',
        ], $route->getOption('_auth'));
    }

    /** @test */
    public function requires_basic_auth(): void
    {
        $route = Route::get(__FUNCTION__, '/');

        $this->assertEmpty($route->getOption('_auth'));

        $route->requiresBasicAuth();

        $this->assertEquals(['basic_auth'], $route->getOption('_auth'));
    }

    /** @test */
    public function requires_cookie_auth(): void
    {
        $route = Route::get(__FUNCTION__, '/');

        $this->assertEmpty($route->getOption('_auth'));

        $route->requiresCookieAuth();

        $this->assertEquals(['cookie'], $route->getOption('_auth'));
    }

    /** @test */
    public function accessible_during_maintenance(): void
    {
        $route = Route::get(__FUNCTION__, '/');

        $this->assertFalse((bool)$route->getOption('_maintenance_access'));

        $route->accessableDuringMaintenance();

        $this->assertTrue((bool)$route->getOption('_maintenance_access'));
    }

    /** @test */
    public function theme(): void
    {
        $route = Route::get(__FUNCTION__, '/');

        $this->assertEmpty($route->getOption('_theme'));

        $route->theme('my_theme');

        $this->assertEquals('my_theme', $route->getOption('_theme'));
    }

    /** @test */
    public function no_cache(): void
    {
        $route = Route::get(__FUNCTION__, '/');

        $this->assertFalse((bool)$route->getOption('no_cache'));

        $route->noCache();

        $this->assertTrue((bool)$route->getOption('no_cache'));
    }

    /** @test */
    public function set_parameter_converter(): void
    {
        $route = Route::get(__FUNCTION__, '/page/{node}/{another_param}');

        $this->assertEmpty($route->getOption('parameters'));

        $route->setParameterConverter('node', 'entity:node');

        $this->assertEquals([
            'node' => [
                'type' => 'entity:node',
            ]
        ], $route->getOption('parameters'));

        $route->setParameterConverter('another_param', 'another_converter');

        $this->assertEquals([
            'node' => [
                'type' => 'entity:node',
            ],
            'another_param' => [
                'type' => 'another_converter',
            ],
        ], $route->getOption('parameters'));
    }

    /** @test */
    public function set_entity_parameter_converter(): void
    {
        $route = Route::get(__FUNCTION__, '/page/{node}/{another_param}');

        $this->assertEmpty($route->getOption('parameters'));

        $route->setEntityParameterConverter('node', 'node');

        $this->assertEquals([
            'node' => [
                'type' => 'entity:node',
            ]
        ], $route->getOption('parameters'));
    }

    /** @test */
    public function override_defined_parameter_converter(): void
    {
        $route = Route::get(__FUNCTION__, '/page/{node}/{another_param}');

        $this->assertEmpty($route->getOption('parameters'));

        $route->setParameterConverter('node', 'entity:node');

        $this->assertEquals([
            'node' => [
                'type' => 'entity:node',
            ]
        ], $route->getOption('parameters'));

        $route->setParameterConverter('node', 'entity:media');

        $this->assertEquals([
            'node' => [
                'type' => 'entity:media',
            ],
        ], $route->getOption('parameters'));
    }

    /** @test */
    public function clear(): void
    {
        Route::clear();

        $getRoute = Route::get('route.get', '/route/get');
        $patchRoute = Route::patch('route.patch', '/route/patch');

        $this->assertEquals([
            'route.get' => $getRoute,
            'route.patch' => $patchRoute,
        ], Route::toArray());

        Route::clear();

        $this->assertEmpty(Route::toArray());
    }

    /** @test */
    public function to_array(): void
    {
        Route::clear();

        $getRoute = Route::get('route.get', '/route/get');
        $patchRoute = Route::patch('route.patch', '/route/patch');
        $postRoute = Route::post('route.post', '/route/post');
        $putRoute = Route::put('route.put', '/route/put');
        $deleteRoute = Route::delete('route.delete', '/route/delete');

        $this->assertEquals([
            'route.get' => $getRoute,
            'route.patch' => $patchRoute,
            'route.post' => $postRoute,
            'route.put' => $putRoute,
            'route.delete' => $deleteRoute,
        ], Route::toArray());
    }

    /** @test */
    public function to_route_collection(): void
    {
        Route::clear();

        $getRoute = Route::get('route.get', '/route/get');
        $patchRoute = Route::patch('route.patch', '/route/patch');
        $postRoute = Route::post('route.post', '/route/post');
        $putRoute = Route::put('route.put', '/route/put');
        $deleteRoute = Route::delete('route.delete', '/route/delete');

        $routeCollection = Route::toRouteCollection();

        $this->assertEquals([
            'route.get' => $getRoute,
            'route.patch' => $patchRoute,
            'route.post' => $postRoute,
            'route.put' => $putRoute,
            'route.delete' => $deleteRoute,
        ], $routeCollection->all());

        $this->assertEquals($getRoute, $routeCollection->get('route.get'));
        $this->assertEquals($patchRoute, $routeCollection->get('route.patch'));
        $this->assertEquals($postRoute, $routeCollection->get('route.post'));
        $this->assertEquals($putRoute, $routeCollection->get('route.put'));
        $this->assertEquals($deleteRoute, $routeCollection->get('route.delete'));
    }

    /** @test */
    public function route_creation(): void
    {
        $symfonyRoute = (new SymfonyRoute('/page/{node}'))
            ->setMethods('GET')
            ->setDefault('_controller', '\Drupal\controller\Controller::view')
            ->setDefault('_title', 'View page')
            ->setDefault('node', '1')
            ->setRequirement('_permission', 'access content,view content')
            ->setRequirement('_role', 'editor')
            ->setRequirement('_module_dependencies', 'node')
            ->setRequirement('_user_is_logged_in', 'TRUE')
            ->setOption('no_cache', 'TRUE')
            ->setOption('parameters', [ //you can use route upcasting here, but just to provide an example
                'node' => [
                    'type' => 'entity:node',
                ],
            ]);

        $route = Route::get('node.page', '/page/{node}')
            ->setController('\Drupal\controller\Controller', 'view')
            ->setTitle('View page')
            ->defaultParamValue('node', '1')
            ->requiresRole('editor')
            ->dependsOnModule('node')
            ->requiresLoginToAccess()
            ->setEntityParameterConverter('node', 'node')
            ->noCache()
            ->requiresAllPermissions([
                'access content',
                'view content',
            ]);

        $this->assertEquals(
            $symfonyRoute->getPath(),
            $route->getPath()
        );
        $this->assertEquals(
            $symfonyRoute->getMethods(),
            $route->getMethods()
        );
        $this->assertEquals(
            $symfonyRoute->getDefault('_controller'),
            $route->getDefault('_controller')
        );
        $this->assertEquals(
            $symfonyRoute->getDefault('_title'),
            $route->getDefault('_title')
        );
        $this->assertEquals(
            $symfonyRoute->getDefault('node'),
            $route->getDefault('node')
        );
        $this->assertEquals(
            $symfonyRoute->getRequirement('_permission'),
            $route->getRequirement('_permission')
        );
        $this->assertEquals(
            $symfonyRoute->getRequirement('_role'),
            $route->getRequirement('_role')
        );
        $this->assertEquals(
            $symfonyRoute->getRequirement('_module_dependencies'),
            $route->getRequirement('_module_dependencies')
        );
        $this->assertEquals(
            $symfonyRoute->getRequirement('_user_is_logged_in'),
            $route->getRequirement('_user_is_logged_in')
        );
        $this->assertEquals(
            $symfonyRoute->getOption('no_cache'),
            $route->getOption('no_cache')
        );
        $this->assertEquals(
            $symfonyRoute->getOption('parameters'),
            $route->getOption('parameters')
        );
    }
}
