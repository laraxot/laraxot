<?php

declare(strict_types=1);

namespace Modules\Blog\View\Components\Article;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\View\Component;
use Modules\Blog\Models\Article;
use Modules\Xot\Actions\GetViewAction;

// use Modules\Xot\View\Components\XotBaseComponent;

/**
 * .
 */
class Meta extends Component
{
    public function __construct(
        public Article $article,
        public string $tpl = 'v1',
    ) {
    }

    public function render(): Renderable
    {
        /**
         * @phpstan-var view-string
         */
        $view = app(GetViewAction::class)->execute($this->tpl);

        $viewParams = [];

        return view($view, $viewParams);
    }
}
