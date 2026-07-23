<?php

declare(strict_types=1);

use function Laravel\Folio\middleware;
use function Laravel\Folio\name;

name('area-personale.pratiche');
middleware(['web', 'auth']);

?><x-layouts.app>
    <x-slot name="title">
        {{ __('pub_theme::ui.header_area_personale.my_practices.label') }}
    </x-slot>

    <main id="main-container" class="container py-4 py-lg-5">
        <div class="row justify-content-center">
            <div class="col-12 col-lg-10">
                <header class="cmp-heading mb-4">
                    <h1 class="title-xxxlarge">{{ __('pub_theme::ui.header_area_personale.my_practices.label') }}</h1>
                    <p class="subtitle-small mb-0">
                        {{ __('pub_theme::ui.header_area_personale.my_practices.description', ['default' => 'Elenco delle pratiche in corso']) }}
                    </p>
                </header>

                <div class="card shadow-sm border-0">
                    <div class="card-body p-4">
                        <p class="text-muted">Questa pagina mostrerà le pratiche disponibili presto.</p>
                    </div>
                </div>
            </div>
        </div>
    </main>
</x-layouts.app>