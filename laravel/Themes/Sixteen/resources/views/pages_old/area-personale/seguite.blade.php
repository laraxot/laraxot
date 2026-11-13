<?php

declare(strict_types=1);

use Illuminate\View\View;
use Modules\Fixcity\Actions\BuildAuthenticatedUserFollowedTicketsQueryAction;
use Modules\Fixcity\Enums\TicketStatusEnum;
use Modules\Fixcity\Models\Ticket;
use Modules\Xot\Contracts\UserContract;

use function Laravel\Folio\middleware;
use function Laravel\Folio\name;
use function Laravel\Folio\render;

name('area-personale.seguite');
middleware(['web', 'auth']);

render(function (View $view): View {
    $user = auth()->user();
    abort_unless($user instanceof UserContract, 403);

    $tickets = app(BuildAuthenticatedUserFollowedTicketsQueryAction::class)
        ->execute($user)
        ->limit(50)
        ->get();

    return $view->with('tickets', $tickets);
});

?>
<x-layouts.app>
    <x-slot name="title">
        {{ __('fixcity::ticket.subscription.followed_title') }}
    </x-slot>

    <main id="main-container" class="mx-auto max-w-5xl px-4 py-12 sm:px-6 lg:px-8">
        <div>
                <header class="mb-8 max-w-3xl">
                    <h1 class="text-3xl font-bold tracking-tight text-slate-950">{{ __('fixcity::ticket.subscription.followed_title') }}</h1>
                    <p class="mt-2 text-lg text-slate-700">{{ __('fixcity::ticket.subscription.followed_description') }}</p>
                </header>


                <p class="mb-5 text-slate-700" role="status">
                    {{ trans_choice('fixcity::ticket.subscription.followed_count', $tickets->count(), ['count' => $tickets->count()]) }}
                </p>

                @if ($tickets->isEmpty())
                    <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                            <p class="mb-5 text-slate-700">{{ __('fixcity::ticket.subscription.followed_empty') }}</p>
                            <a class="inline-flex min-h-11 items-center rounded-lg bg-sky-900 px-5 py-3 font-semibold text-white hover:bg-sky-950 focus:outline-none focus-visible:ring-2 focus-visible:ring-sky-800 focus-visible:ring-offset-2" href="{{ url('/'.request()->segment(1).'/tickets') }}">
                                {{ __('fixcity::ticket.subscription.discover_tickets') }}
                            </a>
                        </div>
                    </div>
                @else
                    <ul class="divide-y divide-slate-200 overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm" aria-label="{{ __('fixcity::ticket.subscription.followed_title') }}">
                        @foreach ($tickets as $ticket)
                            @php
                                /** @var Ticket $ticket */
                                $statusValue = $ticket->resolveTicketStatusValue();
                                $status = TicketStatusEnum::tryFrom($statusValue)?->getLabel() ?? $statusValue;
                                $ticketCode = $ticket->code;
                                $trackUrl = is_string($ticketCode) && trim($ticketCode) !== ''
                                    ? url('/'.request()->segment(1).'/tickets/track?code='.rawurlencode($ticketCode))
                                    : null;
                            @endphp
                            <li class="p-5">
                                <div class="flex flex-col justify-between gap-3 sm:flex-row sm:items-center">
                                    <div>
                                        <h2 class="text-lg font-semibold text-slate-950">{{ $ticket->name }}</h2>
                                        <p class="mb-0 text-sm text-slate-700">
                                            <span>{{ __('fixcity::ticket.fields.status.label') }}: {{ $status }}</span>
                                        </p>
                                    </div>
                                    @if (is_string($trackUrl))
                                        <a class="inline-flex min-h-11 shrink-0 items-center rounded-lg border border-sky-800 px-4 py-2 font-semibold text-sky-900 hover:bg-sky-50 focus:outline-none focus-visible:ring-2 focus-visible:ring-sky-800" href="{{ $trackUrl }}">
                                            {{ __('fixcity::ticket.track.submit') }}
                                            <span class="visually-hidden">: {{ $ticket->name }}</span>
                                        </a>
                                    @else
                                        <span class="text-sm text-slate-600" role="status">
                                            {{ __('fixcity::ticket.subscription.tracking_unavailable') }}
                                        </span>
                                    @endif
                                </div>
                            </li>
                        @endforeach
                    </ul>
                @endif
        </div>
    </main>
</x-layouts.app>
