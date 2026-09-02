<?php

declare(strict_types=1);

namespace Modules\Blog\View\Components;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\View\Component;
use Modules\Blog\Models\Menu as MenuModel;
use Modules\Xot\Actions\GetViewAction;

// use Modules\Xot\View\Components\XotBaseComponent;

/**
 * .
 */
class Menu extends Component
{
    public function __construct(
        public string $name,
        public string $tpl = 'v1',
    ) {
    }

    public function render(): Renderable
    {
        /**
         * @phpstan-var view-string
         */
        $view = app(GetViewAction::class)->execute($this->tpl);
        $menu = MenuModel::firstOrCreate(['name' => $this->name]);

        $viewParams = [
            'menu' => $menu,
        ];
        if (null === $menu->items) {
            $menu->items = [];
            $menu->save();
        }

        return view($view, $viewParams);
    }
}
