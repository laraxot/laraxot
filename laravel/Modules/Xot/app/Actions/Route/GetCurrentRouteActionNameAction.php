<?php

declare(strict_types=1);

namespace Modules\Xot\Actions\Route;

<<<<<<< HEAD
use Illuminate\Routing\Route;
=======
>>>>>>> laraxot/dev
use Illuminate\Support\Str;
use Spatie\QueueableAction\QueueableAction;

class GetCurrentRouteActionNameAction
{
    use QueueableAction;

    public function execute(): string
    {
<<<<<<< HEAD
        $route = request()->route();
        if (! $route instanceof Route) {
            throw new \RuntimeException('Current route action is not available.');
        }

        $routeAction = $route->getActionName();
=======
        $routeAction = app(GetCurrentRouteHandlerAction::class)->execute();
>>>>>>> laraxot/dev
        $action = Str::after($routeAction, '@');
        $action = Str::contains($action, '\\') ? Str::afterLast($action, '\\') : $action;
        $action = Str::endsWith($action, 'Controller') ? Str::before($action, 'Controller') : $action;

        return Str::snake($action);
    }
}
