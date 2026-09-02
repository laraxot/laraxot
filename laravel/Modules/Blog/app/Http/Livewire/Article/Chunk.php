<?php

declare(strict_types=1);

namespace Modules\Blog\Http\Livewire\Article;

use Illuminate\Contracts\Support\Renderable;
use Livewire\Component;
use Modules\Blog\Models\Article;
use Modules\Xot\Actions\GetViewAction;

class Chunk extends Component
{
    /** @var array<int, string> */
    /** @var array<int, mixed> */
    /** @var array<int, mixed> */
    public array $postIds;

    public string $tpl = 'v1';

    public function render(): Renderable
    {
        $articles = Article::whereIn('id', $this->postIds)->get()->keyBy('id');

        $orderedPosts = collect($this->postIds)->map(static fn ($id) => (is_array($articles) ? $articles[$id] : null));

        /**
         * @phpstan-var view-string
         */
        $view = app(GetViewAction::class)->execute($this->tpl);

        $viewParams = [
            'articles' => $orderedPosts,
        ];

        return view((string) $view, $viewParams);
    }

    public function url(): string
    {
        return '#';
    }
}
