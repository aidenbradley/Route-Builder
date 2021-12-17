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

    public function acceptsGet(): self
    {
        return $this->addMethod('GET');
    }

    public function acceptsPost(): self
    {
        return $this->addMethod('POST');
    }

    public function acceptsPatch(): self
    {
        return $this->addMethod('PATCH');
    }

    public function acceptsPut(): self
    {
        return $this->addMethod('PUT');
    }

    public function acceptsDelete(): self
    {
        return $this->addMethod('DELETE');
    }

    public function acceptsMethods(array $methods): self
    {
        foreach ($methods as $method) {
            $this->addMethod($method);
        }

        return $this;
    }

    public function controller(string $controller): self
    {
        return $this->setDefault('_controller', $controller);
    }

    public function form(string $form): self
    {
        return $this->setDefault('_form', $form);
    }

    public function entityView(string $entityView): self
    {
        return $this->setDefault('_entity_view', $entityView);
    }

    public function entityList(string $entityList): self
    {
        return $this->setDefault('_entity_list', $entityList);
    }

    public function entityForm(string $entityForm): self
    {
        return $this->setDefault('_entity_form', $entityForm);
    }

    public function title(string $title): self
    {
        return $this->setDefault('_title', $title);
    }

    public function titleCallback(string $callback): self
    {
        return $this->setDefault('_title_callback', $callback);
    }

    public function titleArguments(): void
    {
        // need to figure out what's accepted here
    }

    public function requiresAllPermissions(array $permissions): self
    {
        return $this->setRequirement('_permission', implode(',', $permissions));
    }

    public function requiresAnyPermission(array $permissions): self
    {
        return $this->setRequirement('_permission', implode('+', $permissions));
    }

    public function requiresAllRoles(array $roles): self
    {
        return $this->setRequirement('_role', implode(',', $roles));
    }

    public function requiresAnyRoles(array $roles): self
    {
        return $this->setRequirement('_role', implode('+', $roles));
    }

    public function defaultAccess(): self
    {
        return $this->setRequirement('_access', 'TRUE');
    }

    public function entityAccess(string $entityAccess): self
    {
        return $this->setRequirement('_entity_access', $entityAccess);
    }

    public function entityValidation(string $entityTypeId, string $regex): self
    {
        return $this->setRequirement($entityTypeId, $regex);
    }

    /** @param string|array $bundles */
    public function entityBundles(string $entityTypeId, $bundles): self
    {
        return $this->setRequirement(
            '_entity_bundles',
            $entityTypeId . ':' . implode('|', (array) $bundles)
        );
    }

    public function entityCreateAccess(string $entityTypeId, string $bundleOrRouteParam): self
    {
        return $this->setRequirement('_entity_create_access', $entityTypeId . ':' . $bundleOrRouteParam);
    }

    public function customAccessCallback(string $accessCheck): self
    {
        return $this->setRequirement('_custom_access', $accessCheck);
    }

    public function jsonFormat(): self
    {
        return $this->format('json');
    }

    public function htmlFormat(): self
    {
        return $this->format('html');
    }

    public function xmlFormat(): self
    {
        return $this->format('xml');
    }

    public function format(string $format): self
    {
        return $this->setRequirement('_format', $format);
    }

    /** Need to add other helper methods for the content type format header */
    public function onlyAcceptsJson(): self
    {
        return $this->setRequirement('_content_type_format', 'json');
    }

    public function onlyAcceptsXml(): self
    {
        return $this->setRequirement('_content_type_format', 'xml');
    }

    public function noCache(): self
    {
        return $this->setOption('no_cache', 'TRUE');
    }

    private function addMethod(string $method): self
    {
        return $this->setMethods(array_merge($this->getMethods(), [
            $method
        ]));
    }
}
