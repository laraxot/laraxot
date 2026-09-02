<?php

declare(strict_types=1);

namespace Modules\Blog\Http\Livewire\Headernav;

use Illuminate\Contracts\Support\Renderable;
use Livewire\Component;
use Modules\Blog\Models\Article;
use Modules\Xot\Actions\GetViewAction;

class SearchBar extends Component
{
    public string $tpl = 'v1';

    public string $search = '';

    public function render(): Renderable
    {
        /**
         * @phpstan-var view-string
         */
        $view = app(GetViewAction::class)->execute($this->tpl);

        $results = [];

        if ('' !== $this->search && '0' !== $this->search) {
            $results = Article::where('title', 'like', '%'.$this->search.'%')->get();
        }

        $viewParams = [
            'results' => $results,
        ];

        return view((string) $view, $viewParams);
    }
}
