<?php

namespace Drupal\route_builder\Routing;

use Symfony\Component\Routing\Route as SymfonyRoute;
use Symfony\Component\Routing\RouteCollection;

class Route extends SymfonyRoute
{
    /** @var array */
    private static $routes = [];

    /** returns an array where the keys are the route names and the values are their respective route definitions */
    public static function toArray(): array
    {
        return static::$routes;
    }

    public static function toRouteCollection(): RouteCollection
    {
        $collection = new RouteCollection();

        foreach (static::$routes as $routeName => $route) {
            $collection->add($routeName, $route);
        }

        return $collection;
    }

    public static function clear(): void
    {
        static::$routes = [];
    }

    /** @return static */
    public static function create(string $routeName, string $path)
    {
        if (isset(static::$routes[$routeName])) {
            throw new \Exception($routeName . ' is already defined');
        }

        static::$routes[$routeName] = new static($path);

        return end(static::$routes);
    }

    /** @return static */
    public static function get(string $routeName, string $path)
    {
        return static::create($routeName, $path)->acceptsGet();
    }

    /** @return static */
    public static function put(string $routeName, string $path)
    {
        return static::create($routeName, $path)->acceptsPut();
    }

    /** @return static */
    public static function patch(string $routeName, string $path)
    {
        return static::create($routeName, $path)->acceptsPatch();
    }

    /** @return static */
    public static function post(string $routeName, string $path)
    {
        return static::create($routeName, $path)->acceptsPost();
    }

    /** @return static */
    public static function delete(string $routeName, string $path)
    {
        return static::create($routeName, $path)->acceptsDelete();
    }

    public function acceptsGet(): self
    {
        return $this->getDefinition()->addMethod('GET');
    }

    public function acceptsPost(): self
    {
        return $this->getDefinition()->addMethod('POST');
    }

    public function acceptsPatch(): self
    {
        return $this->getDefinition()->addMethod('PATCH');
    }

    public function acceptsPut(): self
    {
        return $this->getDefinition()->addMethod('PUT');
    }

    public function acceptsDelete(): self
    {
        return $this->getDefinition()->addMethod('DELETE');
    }

    public function acceptsMethods(array $methods): self
    {
        foreach ($methods as $method) {
            $this->getDefinition()->addMethod($method);
        }

        return $this;
    }

    public function controller(string $controller, string $method = '__invoke'): self
    {
        return $this->getDefinition()->setDefault('_controller', $controller . '::' . $method);
    }

    public function form(string $form): self
    {
        return $this->getDefinition()->setDefault('_form', $form);
    }

    public function entityView(string $entityView): self
    {
        return $this->getDefinition()->setDefault('_entity_view', $entityView);
    }

    public function entityList(string $entityList): self
    {
        return $this->getDefinition()->setDefault('_entity_list', $entityList);
    }

    public function entityForm(string $entityForm): self
    {
        return $this->getDefinition()->setDefault('_entity_form', $entityForm);
    }

    public function title(string $title): self
    {
        return $this->getDefinition()->setDefault('_title', $title);
    }

    public function parameterDefaultValue(string $parameter, string $defaultValue): self
    {
        return $this->getDefinition()->setDefault($parameter, $defaultValue);
    }

    public function titleCallback(string $callback): self
    {
        return $this->getDefinition()->setDefault('_title_callback', $callback);
    }

    public function titleArguments(): void
    {
        // need to figure out what's accepted here
    }

    public function requiresAllPermissions(array $permissions): self
    {
        return $this->getDefinition()->setRequirement('_permission', implode(',', $permissions));
    }

    public function requiresAnyPermission(array $permissions): self
    {
        return $this->getDefinition()->setRequirement('_permission', implode('+', $permissions));
    }

    public function requiresAllRoles(array $roles): self
    {
        return $this->getDefinition()->setRequirement('_role', implode(',', $roles));
    }

    public function requiresAnyRoles(array $roles): self
    {
        return $this->getDefinition()->setRequirement('_role', implode('+', $roles));
    }

    public function defaultAccess(): self
    {
        return $this->getDefinition()->setRequirement('_access', 'TRUE');
    }

    public function entityAccess(string $entityAccess): self
    {
        return $this->getDefinition()->setRequirement('_entity_access', $entityAccess);
    }

    public function entityValidation(string $entityTypeId, string $regex): self
    {
        return $this->getDefinition()->setRequirement($entityTypeId, $regex);
    }

    /** @param string|array $bundles */
    public function entityBundles(string $entityTypeId, $bundles): self
    {
        return $this->getDefinition()->setRequirement(
            '_entity_bundles',
            $entityTypeId . ':' . implode('|', (array) $bundles)
        );
    }

    public function entityCreateAccess(string $entityTypeId, string $bundleOrRouteParam): self
    {
        return $this->getDefinition()->setRequirement('_entity_create_access', $entityTypeId . ':' . $bundleOrRouteParam);
    }

    public function customAccessCallback(string $accessCheck): self
    {
        return $this->getDefinition()->setRequirement('_custom_access', $accessCheck);
    }

    public function returnsJsonFormat(): self
    {
        return $this->getDefinition()->returnsFormat('json');
    }

    public function returnsHtmlFormat(): self
    {
        return $this->getDefinition()->returnsFormat('html');
    }

    public function returnsXmlFormat(): self
    {
        return $this->getDefinition()->returnsFormat('xml');
    }

    public function returnsFormat(string $format): self
    {
        return $this->getDefinition()->setRequirement('_format', $format);
    }

    public function onlyAcceptsJson(): self
    {
        return $this->getDefinition()->setContentTypeFormat('json');
    }

    public function onlyAcceptsXml(): self
    {
        return $this->getDefinition()->setContentTypeFormat('xml');
    }

    public function setContentTypeFormat(string $contentTypeFormat): self
    {
        return $this->getDefinition()->setRequirement('_content_type_format', $contentTypeFormat);
    }

    public function dependsOnAllModules(array $modules): self
    {
        return $this->getDefinition()->setRequirement('_module_dependencies', implode(',', $modules));
    }

    /** @param string|array $modules */
    public function dependsOnAnyModule($modules): self
    {
        return $this->getDefinition()->setRequirement('_module_dependencies', implode('+', $modules));
    }

    public function usesCsrf(): self
    {
        return $this->getDefinition()->setRequirement('_csrf_token', 'TRUE');
    }

    public function requiresCsrfTokenHeader(): self
    {
        return $this->getDefinition()->setRequirement('_csrf_request_header_token', 'TRUE');
    }

    /*
     * Need to figure out a better name for this. Description is as follows -
     *
     * Set this to 'TRUE' (with the single quotes) to have access granted to the
     * route if the user is anonymous AND user registration is not set to
     * "Administrators only" on the site.
     */
    public function accessUserRegister(): self
    {
        return $this->getDefinition()->setRequirement('_access_user_register', 'TRUE');
    }

    public function requiresLoginToAccess(): self
    {
        return $this->getDefinition()->setRequirement('_user_is_logged_in', 'TRUE');
    }

    public function isAdminRoute(): self
    {
        return $this->getDefinition()->setOption('_admin_route', 'TRUE');
    }

    public function requiresBasicAuth(): self
    {
        return $this->getDefinition()->addAuthMechanism('basic_auth');
    }

    public function requiresCookieAuth(): self
    {
        return $this->getDefinition()->addAuthMechanism('cookie');
    }

    /** @param string|array $authMechanisms */
    public function requiresAuthentication($authMechanisms): self
    {
        foreach ((array) $authMechanisms as $authMechanism) {
            $this->getDefinition()->addAuthMechanism($authMechanism);
        }

        return $this;
    }

    public function accessableDuringMaintenance(): self
    {
        return $this->getDefinition()->setOption('_maintenance_access', 'TRUE');
    }

    // need to find documentation on this
    public function theme(string $theme): self
    {
        return $this->getDefinition()->setOption('_theme', $theme);
    }

    public function noCache(): self
    {
        return $this->getDefinition()->setOption('no_cache', 'TRUE');
    }

    public function setParameterConverter(string $paramName, string $convertName): self
    {
        $paramConverters = [
            $paramName => [
                'type' => $convertName,
            ],
        ];

        if ($this->getDefinition()->getOption('parameters') !== null) {
            $paramConverters = array_merge($this->getDefinition()->getOption('parameters'), $paramConverters);
        }

        return $this->getDefinition()->setOption('parameters', $paramConverters);
    }

    /** Gets the current route definition */
    public function getDefinition(): Route
    {
        return end(static::$routes);
    }

    private function addAuthMechanism(string $authMechanism): self
    {
        $authOption = $this->getDefinition()->getOption('_auth');

        if ($authOption !== null) {
            $authMechanism = array_merge($this->getDefinition()->getOption('_auth'), [
                $authMechanism
            ]);
        }

        return $this->getDefinition()->setOption('_auth', (array) $authMechanism);
    }

    private function addMethod(string $method): self
    {
        return $this->getDefinition()->setMethods(array_merge($this->getDefinition()->getMethods(), [
            $method
        ]));
    }
}
