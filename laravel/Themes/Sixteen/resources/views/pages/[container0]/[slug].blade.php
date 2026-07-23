<?php

declare(strict_types=1);

use function Laravel\Folio\middleware;
use function Laravel\Folio\name;
use Livewire\Volt\Component;
use Modules\Cms\Actions\ResolvePageAction;
use Modules\Cms\Http\Middleware\PageSlugMiddleware;

name('cms.view');
middleware(PageSlugMiddleware::class);

new class extends Component {
    public string $container = '';
    public string $slug = '';
    public string $pageSlug = '';

    /** @var array<string, mixed> */
    public array $data = [];

    public function mount(string $container, string $slug = ''): void
    {
        $this->container = $container;
        $this->slug = $slug;

        $resolved = app(ResolvePageAction::class)->execute($container, $slug);

        $this->pageSlug = $resolved->pageSlug;

        $item = $resolved->item;

        $this->data = [
            'container' => $container,
            'slug' => $slug,
            'container0' => $container,
            'slug0' => $slug,
            'item' => $item,
        ];
    }
};
?>

<x-layouts.app>
    @volt('cms.view')
    <div class="page-content content" data-slug="{{ $this->pageSlug }}" data-side="content">
        <x-page side="content" :slug="$this->pageSlug" :data="$this->data" />
    </div>
    @endvolt
</x-layouts.app>
