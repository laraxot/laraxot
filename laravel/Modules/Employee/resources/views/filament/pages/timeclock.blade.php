<?php

declare(strict_types=1);

?>
<x-filament-panels::page>
    <div class="space-y-6">
        <!-- Time Clock Component -->
        <livewire:employee::time-clock />
        
        <!-- Dashboard Component -->
        <div class="mt-8">
            <livewire:employee::work-hour-dashboard />
        </div>
    </div>
</x-filament-panels::page>
