<?php

declare(strict_types=1);

use function Laravel\Folio\middleware;
use function Laravel\Folio\name;
use Livewire\Volt\Component;
use Modules\Cms\Actions\ResolvePageAction;
use Modules\Cms\Http\Middleware\PageSlugMiddleware;

name('container0.view');
middleware(PageSlugMiddleware::class);

new class extends Component {
    public string $container0 = '';
    public string $slug0 = '';

    public string $pageSlug = '';

    /** @var array<string, mixed> */
    public array $data = [];

    public function mount(string $container0, string $slug0 = ''): void
    {
        $this->container0 = $container0;
        $this->slug0 = $slug0;

        $resolved = app(ResolvePageAction::class)->execute($container0, $slug0);

        $this->pageSlug = $resolved->pageSlug;

        $item = $resolved->item;

        $this->data = [
            'container0' => $container0,
            'slug0' => $slug0,
            'slug' => $slug0,
            'item' => $item,
        ];
    }
};
?>

<x-layouts.app>
    @volt('container0.view')
    <div class="page-content content" data-slug="{{ $this->pageSlug }}" data-side="content">
        <x-page side="content" :slug="$this->pageSlug" :data="$this->data" />
    </div>
    @endvolt
</x-layouts.app>
