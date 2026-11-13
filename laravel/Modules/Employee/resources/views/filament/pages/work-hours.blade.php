<?php

declare(strict_types=1);

?>
<x-filament-panels::page>
    {{-- Il pannello non ha tema Tailwind (space-y-6/grid-cols-* non esistono nel CSS): CSS nativo inline, 1 colonna su telefono. --}}
    <div style="display:flex;flex-direction:column;gap:1.5rem;">
        <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(min(100%,12rem),1fr));gap:1.5rem;">
            <x-filament::section>
                <x-slot name="heading">{{ __('employee::work_hours.page.current_status.label') }}</x-slot>
                <div style="font-size:1.5rem;font-weight:700;">
                    {{ __("employee::enums.work_hour_status.{$currentStatus}") }}
                </div>
            </x-filament::section>

            <x-filament::section>
                <x-slot name="heading">{{ __('employee::work_hours.page.hours_today.label') }}</x-slot>
                <div style="font-size:1.5rem;font-weight:700;">
                    {{ $workHoursToday }}
                </div>
            </x-filament::section>
        </div>

        {{ $this->table }}
    </div>
</x-filament-panels::page>
