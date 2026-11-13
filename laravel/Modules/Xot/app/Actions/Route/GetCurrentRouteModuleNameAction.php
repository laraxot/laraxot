<?php

declare(strict_types=1);

namespace Modules\Xot\Actions\Route;

<<<<<<< HEAD
use Illuminate\Routing\Route;
=======
>>>>>>> laraxot/dev
use Illuminate\Support\Str;
use Spatie\QueueableAction\QueueableAction;

class GetCurrentRouteModuleNameAction
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

        return Str::between($routeAction, 'Modules\\', '\\Http');
    }
}
