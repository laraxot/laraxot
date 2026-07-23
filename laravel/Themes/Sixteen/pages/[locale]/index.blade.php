<?php

use function Laravel\Folio\middleware;
use function Laravel\Folio\name;
use Livewire\Volt\Component;
use Modules\Cms\Http\Middleware\PageSlugMiddleware;

name('home');
middleware(PageSlugMiddleware::class);

new class extends Component {
    public string $slug = '';

    public string $pageSlug = '';

    /** @var array<string, mixed> */
    public array $data = [];

    public function mount(string $slug = ''): void
    {
        $this->slug = $slug;
        $this->pageSlug = 'tests';
        $this->data = ['slug' => $slug];
    }
};
?>

<x-layouts.app>
    <x-page side="content" :slug="$pageSlug" :data="$data" />
</x-layouts.app>