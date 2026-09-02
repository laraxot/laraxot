<?php

declare(strict_types=1);

namespace Modules\Blog\View\Components\Article;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\View\Component;
use Modules\Xot\Actions\GetViewAction;

// use Modules\Xot\View\Components\XotBaseComponent;

/**
 * .
 */
class Footer extends Component
{
    /** @var array<int, array<string, mixed>> */
    public array $blocks = [];

    /**
     * @param array<int, array<string, mixed>>|string|null $blocks
     */
    public function __construct(
        array|string|null $blocks,
        public Model $article,
        public string $tpl = 'v1',
    ) {
        if (! \is_array($blocks)) {
            $blocks = [];
        }
        $this->blocks = $blocks;
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
