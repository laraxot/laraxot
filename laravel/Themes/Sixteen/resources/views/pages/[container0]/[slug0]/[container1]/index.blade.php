<?php

declare(strict_types=1);

use function Laravel\Folio\middleware;
use function Laravel\Folio\name;
use Livewire\Volt\Component;
use Modules\Cms\Http\Middleware\PageSlugMiddleware;

name('container1.index');
middleware(PageSlugMiddleware::class);

new class extends Component {
    public string $container0 = '';
    public string $slug0 = '';
    public string $container1 = '';

    public string $pageSlug = '';

    /** @var array<string, mixed> */
    public array $data = [];

    public function mount(string $container0, string $slug0 = '', string $container1 = ''): void
    {
        $this->container0 = $container0;
        $this->slug0 = $slug0;
        $this->container1 = $container1;
        $this->pageSlug = $container0.'.view';
        $this->data = [
            'container0' => $container0,
            'slug0' => $slug0,
            'container1' => $container1,
        ];
    }
};
?>

<x-layouts.app>
    @volt('container1.index')
    <div class="page-content content" data-slug="{{ $pageSlug }}" data-side="content">
        <x-page side="content" :slug="$pageSlug" :data="$data" />
    </div>
    @endvolt
</x-layouts.app>
