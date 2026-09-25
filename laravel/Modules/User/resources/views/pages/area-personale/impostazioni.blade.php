<?php

declare(strict_types=1);

use Livewire\Volt\Component;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;
use Modules\Cms\Http\Middleware\PageSlugMiddleware;
use function Laravel\Folio\middleware;
use function Laravel\Folio\name;

name('area-personale.impostazioni');
middleware(PageSlugMiddleware::class);

new class extends Component {
    public function mount(): void
    {
        if (! auth()->check()) {
            $this->redirect(LaravelLocalization::localizeURL('/auth/login'));
        }
    }
};
?>

<x-layouts.app>
    <x-slot name="title">
        {{ __('pub_theme::ui.header_area_personale.settings.label') }}
    </x-slot>

    @volt('area-personale.impostazioni')
        <main id="main-container" class="container py-4 py-lg-5">
            <div class="row justify-content-center">
                <div class="col-12 col-lg-10">
                    <header class="cmp-heading mb-4">
                        <h1 class="title-xxxlarge">{{ __('pub_theme::ui.header_area_personale.settings.label') }}</h1>
                        <p class="subtitle-small mb-0">
                            {{ __('pub_theme::ui.header_area_personale.settings.description', ['default' => 'Impostazioni account e preferenze']) }}
                        </p>
                    </header>

                    <div class="card shadow-sm border-0">
                        <div class="card-body p-4">
                            <p class="text-muted">Questa pagina mostrerà le impostazioni disponibili presto.</p>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    @endvolt
</x-layouts.app>
