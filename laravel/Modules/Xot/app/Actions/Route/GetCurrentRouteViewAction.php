<?php

declare(strict_types=1);

namespace Modules\Xot\Actions\Route;

use Illuminate\Routing\Route;
use Illuminate\Support\Str;
use Spatie\QueueableAction\QueueableAction;

class GetCurrentRouteViewAction
{
    use QueueableAction;

    /**
     * Gets the current route's view identifier as a dotted path.
     *
     * Returns a string like "module.item" derived from controller name
     * and route parameters.
     */
    public function execute(): string
    {
        $route = request()->route();
        if (! $route instanceof Route) {
            throw new \RuntimeException('Current route action is not available.');
        }

        $routeAction = $route->getActionName();
        $controller = Str::between($routeAction, 'Http\\Controllers\\', 'Controller');
        /**
         * Route parameters are polymorphic: they can be scalars, model instances,
         * or any other type. Typed as array<string, mixed> to reflect reality.
         *
         * @var array<string, mixed> $params
         */
        $params = [];
        foreach ($route->parameters() as $key => $value) {
            if (is_string($key)) {
                $params[$key] = $value;
            }
        }

        $params['containers'] = implode('.', array_map(
            static fn (mixed $value): string => is_scalar($value) ? (string) $value : '',
            array_values(array_filter(
                $params,
                static fn (string $key): bool => str_starts_with($key, 'container'),
                ARRAY_FILTER_USE_KEY,
            )),
        ));

        return collect(explode('\\', $controller))
            ->reject(static fn (string $part): bool => in_array($part, ['Module', 'Item'], true))
            ->map(static function (string $part) use ($params): string {
                $part = Str::snake($part);
                $value = $params[$part] ?? null;

                return is_string($value) ? $value : $part;
            })
            ->implode('.');
    }
}
