<?php

declare(strict_types=1);

?>
{{--
    Mobile-first (story docs/stories/mobile-ux-pass.story.md).
    Il pannello non ha un tema Tailwind: le utility (flex, gap-6, h-24, text-5xl...) non esistono nel
    CSS compilato di Filament, quindi qui si usa CSS nativo inline. `auto-fit/minmax` mette i tre
    blocchi in colonna su telefono e in riga da ~50 rem in su, senza media query.
    Ordine DOM = ordine di lettura su telefono: ora -> pulsante -> timbrature (il tasto resta in alto,
    sotto il pollice, anche con molte timbrature).
--}}
<x-filament-widgets::widget>
    <x-filament::section>
        {{-- 15 s e solo a scheda visibile: 1 richiesta/s su rete lenta bloccava la coda di Livewire. --}}
        <div
            wire:poll.15s.visible="updateData"
            style="display:grid;grid-template-columns:repeat(auto-fit,minmax(min(100%,16rem),1fr));gap:1.25rem;align-items:center;text-align:center;"
        >
            <div>
                <div style="font-size:3rem;font-weight:700;line-height:1;font-variant-numeric:tabular-nums;">
                    {{ $currentTime }}
                </div>
                <div style="margin-top:0.25rem;font-size:0.875rem;opacity:0.7;">
                    {{ $todayDate }}
                </div>
            </div>

            <div>
                {{-- Bersaglio di tocco: tutta la larghezza e >= 56 px, un solo gesto con una mano. --}}
                @if($isClockedIn)
                    <x-filament::button
                        wire:click="clockOut"
                        color="danger"
                        size="xl"
                        icon="heroicon-o-arrow-left-on-rectangle"
                        style="width:100%;min-height:3.5rem;font-size:1.125rem;">
                        {{ __('employee::time_clock_widget.actions.clock_out.label') }}
                    </x-filament::button>
                @else
                    <x-filament::button
                        wire:click="clockIn"
                        color="success"
                        size="xl"
                        icon="heroicon-o-arrow-right-on-rectangle"
                        style="width:100%;min-height:3.5rem;font-size:1.125rem;">
                        {{ __('employee::time_clock_widget.actions.clock_in.label') }}
                    </x-filament::button>
                @endif
            </div>

            <div aria-live="polite">
                <div style="margin-bottom:0.5rem;">
                    <x-filament::badge :color="$isClockedIn ? 'success' : 'gray'">
                        {{ $isClockedIn ? __('employee::time_clock_widget.messages.session_active.message') : __('employee::time_clock_widget.messages.session_inactive.message') }}
                    </x-filament::badge>
                </div>

                <div style="display:flex;flex-wrap:wrap;gap:0.25rem;justify-content:center;">
                    @forelse($todayEntries as $entry)
                        <x-filament::badge
                            wire:key="entry-{{ $loop->index }}"
                            :color="$entry['type'] === 'clock_in' ? 'success' : 'danger'"
                            size="sm">
                            {{ $entry['type'] === 'clock_in' ? '→' : '←' }} {{ $entry['time'] }}
                        </x-filament::badge>
                    @empty
                        <x-filament::badge color="gray" size="sm" icon="heroicon-o-clock">
                            {{ __('employee::time_clock_widget.messages.no_entries.message') }}
                        </x-filament::badge>
                    @endforelse
                </div>
            </div>
        </div>
    </x-filament::section>
</x-filament-widgets::widget>
