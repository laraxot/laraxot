<?php

declare(strict_types=1);

use Illuminate\View\View;
use function Laravel\Folio\middleware;
use function Laravel\Folio\name;
use function Laravel\Folio\render;

use Modules\Fixcity\Actions\BuildAuthenticatedUserTicketsQueryAction;
use Modules\Fixcity\Models\Ticket;
use Modules\Xot\Actions\Cast\SafeStringCastAction;

name('area-personale.pratiche');
middleware(['web', 'auth']);

render(function (View $view): View {
    $tickets = app(BuildAuthenticatedUserTicketsQueryAction::class)
        ->execute()
        ->limit(50)
        ->get();

    return $view->with('tickets', $tickets);
});

?>
<x-layouts.app>
    <x-slot name="title">
        {{ __('pub_theme::ui.header_area_personale.my_practices.label') }}
    </x-slot>

    <main id="main-container" class="container py-4 py-lg-5">
        <div class="row justify-content-center">
            <div class="col-12 col-lg-10">
                <header class="cmp-heading mb-4">
                    <h1 class="title-xxxlarge">{{ __('pub_theme::ui.header_area_personale.my_practices.label') }}</h1>
                    <p class="subtitle-small mb-0">
                        {{ __('fixcity::ticket.pratiche.description') }}
                    </p>
                </header>

                <div class="d-flex justify-content-between align-items-center mb-3 gap-2 flex-wrap">
                    <p class="mb-0 text-muted">{{ trans_choice('fixcity::ticket.pratiche.count', $tickets->count(), ['count' => $tickets->count()]) }}</p>
                    <div class="d-flex gap-2 flex-wrap">
                        <a class="btn btn-outline-primary" href="{{ url('/'.request()->segment(1).'/area-personale/seguite') }}">
                            {{ __('fixcity::ticket.subscription.followed_title') }}
                        </a>
                        <a class="btn btn-outline-primary" href="{{ url('/'.request()->segment(1).'/area-personale/impostazioni') }}">
                            {{ __('fixcity::ticket_notification_preferences.page_title') }}
                        </a>
                        <a class="btn btn-primary" href="{{ url('/'.request()->segment(1).'/tickets/create') }}">
                            {{ __('fixcity::ticket.actions.create.label') }}
                        </a>
                    </div>
                </div>

                @if ($tickets->isEmpty())
                    <div class="card shadow-sm border-0">
                        <div class="card-body p-4">
                            <p class="text-muted mb-0">{{ __('fixcity::ticket.pratiche.empty') }}</p>
                        </div>
                    </div>
                @else
                    <div class="list-group shadow-sm">
                        @foreach ($tickets as $ticket)
                            @php
                                /** @var Ticket $ticket */
                                $code = SafeStringCastAction::cast($ticket->code);
                                $status = $ticket->resolveTicketStatusValue();
                                $trackUrl = $code !== ''
                                    ? url('/'.request()->segment(1).'/tickets/track/'.$code)
                                    : null;
                            @endphp
                            <div class="list-group-item list-group-item-action py-3">
                                <div class="d-flex flex-column flex-sm-row w-100 justify-content-between align-items-sm-center gap-3">
                                    <div>
                                        <h2 class="h5 mb-1">{{ $ticket->name }}</h2>
                                        <p class="mb-1 text-muted small">
                                            @if ($code !== '')
                                                <span class="me-2"><code>{{ $code }}</code></span>
                                            @endif
                                            <span>{{ $status }}</span>
                                        </p>
                                    </div>
                                    <div class="d-flex flex-column flex-sm-row gap-2">
                                        <a
                                            class="btn btn-sm btn-outline-primary"
                                            href="{{ url('/'.request()->segment(1).'/tickets/'.$ticket->getKey()) }}"
                                            aria-label="{{ __('pub_theme::home.map.details') }}: {{ $ticket->name }}"
                                        >
                                            {{ __('pub_theme::home.map.details') }}
                                        </a>
                                        @if (is_string($trackUrl) && $trackUrl !== '')
                                            <a class="btn btn-sm btn-outline-primary" href="{{ $trackUrl }}">
                                                {{ __('fixcity::ticket.track.submit') }}
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </main>
</x-layouts.app>
