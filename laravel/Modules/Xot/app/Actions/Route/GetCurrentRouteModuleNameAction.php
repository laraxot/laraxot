<?php

declare(strict_types=1);

namespace Modules\Xot\Actions\Route;

<<<<<<< HEAD
=======
use Illuminate\Routing\Route;
>>>>>>> 77b1e2748 (first)
use Illuminate\Support\Str;
use Spatie\QueueableAction\QueueableAction;

class GetCurrentRouteModuleNameAction
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

        return Str::between($routeAction, 'Modules\\', '\\Http');
    }
}
