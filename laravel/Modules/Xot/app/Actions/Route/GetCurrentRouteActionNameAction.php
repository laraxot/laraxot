<?php

declare(strict_types=1);

namespace Modules\Xot\Actions\Route;

<<<<<<< HEAD
=======
use Illuminate\Routing\Route;
>>>>>>> 77b1e2748 (first)
use Illuminate\Support\Str;
use Spatie\QueueableAction\QueueableAction;

class GetCurrentRouteActionNameAction
{
    use QueueableAction;

    public function execute(): string
    {
<<<<<<< HEAD
        $routeAction = app(GetCurrentRouteHandlerAction::class)->execute();
=======
        $route = request()->route();
        if (! $route instanceof Route) {
            throw new \RuntimeException('Current route action is not available.');
        }

        $routeAction = $route->getActionName();
>>>>>>> 77b1e2748 (first)
        $action = Str::after($routeAction, '@');
        $action = Str::contains($action, '\\') ? Str::afterLast($action, '\\') : $action;
        $action = Str::endsWith($action, 'Controller') ? Str::before($action, 'Controller') : $action;

        return Str::snake($action);
    }
}
