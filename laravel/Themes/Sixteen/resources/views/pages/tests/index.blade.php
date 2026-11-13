<?php

declare(strict_types=1);

use Livewire\Attributes\Locked;
use Livewire\Volt\Component;
use Modules\Cms\Http\Middleware\PageSlugMiddleware;

use function Laravel\Folio\middleware;
use function Laravel\Folio\name;

name('tests.index');
middleware(PageSlugMiddleware::class);

new class extends Component
{
    #[Locked]
    public string $pageSlug = 'tests.index';

    /** @var array<string, mixed> */
    #[Locked]
    public array $data = [];

    public function mount(): void
    {
        $this->pageSlug = 'tests.index';
        $this->data = [];
    }
};
?>

@php(view()->share('dcRelativeAssets', true))
<x-layouts.design-comuni-bare>
    @volt('tests.index')
    <div class="design-comuni-template-index page-content content" data-dc data-slug="{{ $pageSlug }}" data-side="content">
        <x-page side="content" :slug="$pageSlug" :data="$data" />
    </div>
    @endvolt
</x-layouts.design-comuni-bare>
