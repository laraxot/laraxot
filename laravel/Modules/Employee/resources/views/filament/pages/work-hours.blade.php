<?php

declare(strict_types=1);

?>
<x-filament-panels::page>
    <div class="space-y-6">
        <div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-4">
            <x-filament::section>
                <x-slot name="heading">Status Attuale</x-slot>
                <div class="text-2xl font-bold">
                    {{ __("employee::enums.work_hour_status.{$currentStatus}") }}
                </div>
            </x-filament::section>

            <x-filament::section>
                <x-slot name="heading">Ore Oggi</x-slot>
                <div class="text-2xl font-bold">
                    {{ $workHoursToday }}
                </div>
            </x-filament::section>
        </div>

        {{ $this->table }}
    </div>
</x-filament-panels::page>
