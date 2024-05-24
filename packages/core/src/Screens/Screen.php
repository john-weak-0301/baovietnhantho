<?php

namespace Core\Screens;

use ReflectionMethod;

abstract class Screen extends \Orchid\Screen\Screen
{
    use ResolvesCards,
        ResolvesActions;

    /**
     * {@inheritdoc}
     */
    public function handle(...$parameters)
    {
        $routeMethod = request()->route('method');

        if ($routeMethod && $method = ScreenMethod::determine($routeMethod, $this)) {
            return $this->callHandleMethod($method, $parameters ?: []);
        }

        return parent::handle(...$parameters);
    }

    /**
     * Returns current User.
     *
     * @return \Core\User\User|\App\User\User
     */
    protected function getCurrentUser()
    {
        return once(function () {
            return $this->request->user();
        });
    }

    /**
     * Determine if the entity has a given ability.
     *
     * @param  string  $ability
     * @param  array|mixed  $arguments
     * @return bool
     */
    protected function currentUserCan($ability, $arguments = []): bool
    {
        return once(function () use ($ability, $arguments) {
            return $this->getCurrentUser()->can($ability, $arguments);
        });
    }

    /**
     * Call custom handle method.
     *
     * @param $method
     * @param  array  $parameters
     * @return mixed
     * @throws \ReflectionException
     */
    protected function callHandleMethod($method, array $parameters)
    {
        array_shift($parameters);

        $this->arguments = $this->resolvesMethodDependencies($parameters, $method);

        return $this->{$method}(...$this->arguments);
    }

    /**
     * Resolve the object method's type-hinted dependencies.
     *
     * @param  array  $parameters
     * @param  string  $method
     * @return array
     *
     * @throws \ReflectionException
     */
    protected function resolvesMethodDependencies(array $parameters, $method): array
    {
        $route = $this->request->route();

        return $route->resolveMethodDependencies(
            $parameters,
            new ReflectionMethod($this, $method)
        );
    }
}
