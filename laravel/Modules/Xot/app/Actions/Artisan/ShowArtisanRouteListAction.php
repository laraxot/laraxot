<?php

declare(strict_types=1);

namespace Modules\Xot\Actions\Artisan;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Route;
use Spatie\QueueableAction\QueueableAction;
use Webmozart\Assert\Assert;

/**
 * Replaces Modules\Xot\Services\ArtisanService::showRouteList().
 */
class ShowArtisanRouteListAction
{
    use QueueableAction;

    public function execute(): string
    {
        $routeCollection = Route::getRoutes();

<<<<<<< HEAD
        /** @var view-string $view */
=======
>>>>>>> laraxot/dev
        $view = 'xot::acts.artisan.show_route_list';
        $view_params = [
            'view' => $view,
            'routeCollection' => $routeCollection,
            'lang' => app()->getLocale(),
        ];

<<<<<<< HEAD
        $result = view($view, $view_params);
=======
        $result = view('xot::acts.artisan.show_route_list', $view_params);
>>>>>>> laraxot/dev
        Assert::isInstanceOf($result, View::class);

        return $result->render();
    }
}
