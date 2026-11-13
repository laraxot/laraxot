<?php

declare(strict_types=1);

use Illuminate\Pagination\LengthAwarePaginator;
use Livewire\Volt\Component;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;
use Modules\Cms\Http\Middleware\PageSlugMiddleware;
use Modules\Fixcity\Actions\BuildAuthenticatedUserTicketsQueryAction;
use function Laravel\Folio\middleware;
use function Laravel\Folio\name;

name('area-personale.pratiche');
middleware(PageSlugMiddleware::class);

new class extends Component {
    /** @var LengthAwarePaginator<int, \Modules\Fixcity\Models\Ticket> */
    public LengthAwarePaginator $tickets;

    public function mount(): void
    {
        if (! auth()->check()) {
            $this->redirect(LaravelLocalization::localizeURL('/auth/login'));

            return;
        }

<<<<<<< .merge_file_j7JxHF
        $this->tickets = app(BuildAuthenticatedUserTicketsQueryAction::class)
            ->execute()
            ->paginate(15);
=======
        $this->tickets = class_exists(BuildAuthenticatedUserTicketsQueryAction::class)
            ? app(BuildAuthenticatedUserTicketsQueryAction::class)
                ->execute()
                ->paginate(15)
            : new LengthAwarePaginator([], 0, 15);
>>>>>>> .merge_file_iB6Zbv
    }
};
?>

<x-layouts.app>
    <x-slot name="title">
        {{ __('pub_theme::ui.header_area_personale.my_practices.label') }}
    </x-slot>

<<<<<<< .merge_file_j7JxHF
    <main id="main-container" class="container py-4 py-lg-5">
        <div class="row justify-content-center">
            <div class="col-12 col-lg-10">
                <header class="cmp-heading mb-4">
                    <h1 class="title-xxxlarge">{{ __('pub_theme::ui.header_area_personale.my_practices.label') }}</h1>
                    <p class="subtitle-small mb-0">
                        {{ __('pub_theme::ui.header_area_personale.my_practices.description', ['default' => 'Elenco delle pratiche in corso']) }}
                    </p>
                </header>

                @volt('area-personale.pratiche')
                    @if ($tickets->isEmpty())
                        <div class="card shadow-sm border-0">
                            <div class="card-body p-4 p-lg-5 text-center">
                                <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" class="text-muted mb-3" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                <p class="text-muted mb-3">
                                    {{ __('pub_theme::ui.header_area_personale.my_practices.empty', ['default' => 'Non hai ancora inviato nessuna segnalazione.']) }}
                                </p>
                                <a href="{{ LaravelLocalization::localizeURL('/tickets/create') }}" class="btn btn-primary">
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
                @endvolt
            </div>
        </div>
=======
    <main id="main-container" class="mx-auto max-w-5xl px-4 py-12 sm:px-6 lg:px-8">
        <header class="mb-8 max-w-3xl">
            <h1 class="text-3xl font-bold tracking-tight text-slate-950">{{ __('pub_theme::ui.header_area_personale.my_practices.label') }}</h1>
            <p class="mt-2 text-lg text-slate-700">{{ __('pub_theme::ui.header_area_personale.my_practices.description', ['default' => 'Elenco delle pratiche in corso']) }}</p>
        </header>
        @volt('area-personale.pratiche')
            @if ($tickets->isEmpty())
                <div class="rounded-2xl border border-slate-200 bg-white p-8 text-center shadow-sm">
                    <p class="text-slate-700">{{ __('pub_theme::ui.header_area_personale.my_practices.empty', ['default' => 'Non hai ancora inviato nessuna segnalazione.']) }}</p>
                    <a href="{{ LaravelLocalization::localizeURL('/tickets/create') }}" class="mt-5 inline-flex min-h-11 items-center rounded-lg bg-sky-900 px-5 py-3 font-semibold text-white hover:bg-sky-950 focus:outline-none focus-visible:ring-2 focus-visible:ring-sky-800 focus-visible:ring-offset-2">{{ __('pub_theme::ui.header_area_personale.my_practices.new_report', ['default' => 'Invia una nuova segnalazione']) }}</a>
                </div>
            @else
                <div class="divide-y divide-slate-200 overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm" role="list">
                    @foreach ($tickets as $ticket)
                        <article class="p-5 sm:p-6" role="listitem">
                            <h2 class="text-lg font-semibold text-slate-950">{{ $ticket->name }}</h2>
                            @if ($ticket->content)<p class="mt-1 text-slate-700">{{ \Illuminate\Support\Str::limit(strip_tags((string) $ticket->content), 140) }}</p>@endif
                            <p class="mt-2 text-sm text-slate-600">{{ __('user::area_personale.practices.submitted_on.label', ['date' => $ticket->created_at?->translatedFormat('d F Y')]) }}</p>
                        </article>
                    @endforeach
                </div>
                <div class="mt-6">{{ $tickets->links() }}</div>
            @endif
        @endvolt
>>>>>>> .merge_file_iB6Zbv
    </main>
</x-layouts.app>
