<?php

declare(strict_types=1);

namespace Modules\UI\View\Components;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\View\Component;
use Modules\Xot\Actions\GetViewAction;

// use Modules\Xot\View\Components\XotBaseComponent;

/**
 * .
 */
final class Navbar extends Component
{
<<<<<<< HEAD
    public function __construct() {}
=======
    /**
     * Create a new component instance.
     */
    public function __construct()
    {
    }
>>>>>>> f5ffd8b4 (.)

    public function render(): Renderable
    {
        /**
         * @phpstan-var view-string
         */
        $view = app(GetViewAction::class)->execute();
        dddx($view);
        $view_params = [];

        return view($view, $view_params);
    }
}
