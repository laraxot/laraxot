<?php

declare(strict_types=1);

use function Laravel\Folio\middleware;
use function Laravel\Folio\name;
use Modules\Fixcity\Actions\BuildAuthenticatedUserTicketsQueryAction;

name('area-personale.pratiche');
middleware(['web', 'auth']);

$tickets = app(BuildAuthenticatedUserTicketsQueryAction::class)->execute()->paginate(15);

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

                @if ($tickets->isEmpty())
                    <div class="card shadow-sm border-0">
                        <div class="card-body p-4 p-lg-5 text-center">
                            <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" class="text-muted mb-3" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            <p class="text-muted mb-3">
                                {{ __('pub_theme::ui.header_area_personale.my_practices.empty', ['default' => 'Non hai ancora inviato nessuna segnalazione.']) }}
                            </p>
                            <a href="{{ route('segnalazioni') }}" class="btn btn-primary">
                                {{ __('pub_theme::ui.header_area_personale.my_practices.new_report', ['default' => 'Invia una nuova segnalazione']) }}
                            </a>
                        </div>
                    </div>
                @else
                    <div class="list-group shadow-sm" role="list">
                        @foreach ($tickets as $ticket)
                            <div class="list-group-item p-4" role="listitem">
                                <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-start gap-2">
                                    <div class="flex-grow-1">
                                        <h2 class="h6 mb-1">{{ $ticket->name }}</h2>
                                        @if ($ticket->content)
                                            <p class="text-muted small mb-1">{{ \Illuminate\Support\Str::limit(strip_tags((string) $ticket->content), 140) }}</p>
                                        @endif
                                        <p class="text-muted small mb-0">
                                            {{ __('pub_theme::ui.header_area_personale.my_practices.submitted_on', ['default' => 'Inviata il']) }}
                                            {{ $ticket->created_at?->translatedFormat('d F Y') }}
                                        </p>
                                    </div>
                                    <div class="flex-shrink-0">
                                        <x-pub_theme::data-display.badge.status :status="$ticket->status" />
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="mt-4">
                        {{ $tickets->links() }}
                    </div>
                @endif
            </div>
        </div>
    </main>
</x-layouts.app>
