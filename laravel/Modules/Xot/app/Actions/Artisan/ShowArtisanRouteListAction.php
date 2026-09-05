<?php

declare(strict_types=1);

namespace Modules\Xot\Actions\Artisan;

use Illuminate\Support\Facades\Route;
use Spatie\QueueableAction\QueueableAction;

/**
 * Replaces Modules\Xot\Services\ArtisanService::showRouteList().
 */
class ShowArtisanRouteListAction
{
    use QueueableAction;

    public function execute(): string
    {
        $routeCollection = Route::getRoutes();

        /**
         * @phpstan-var view-string
         */
        $view = 'xot::acts.artisan.show_route_list';
        $view_params = [
            'view' => $view,
            'routeCollection' => $routeCollection,
            'lang' => app()->getLocale(),
        ];

        return view($view, $view_params)->render();
    }
}
