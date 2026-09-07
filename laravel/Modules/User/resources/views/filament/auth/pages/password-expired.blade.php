<?php

declare(strict_types=1);

?>
<x-filament-panels::page>
    
<<<<<<< HEAD
<<<<<<< HEAD
    <form wire:submit="resetPassword">
=======
    <x-filament-schemas::form wire:submit="resetPassword">
>>>>>>> f548be94 (.)
=======
    <x-filament-schemas::form wire:submit="resetPassword">
=======
    <form wire:submit="resetPassword">
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
        {{ $this->form }}

        <x-filament::actions
            :actions="$this->getCachedFormActions()"
            :full-width="$this->hasFullWidthFormActions()"
        />
<<<<<<< HEAD
<<<<<<< HEAD
    </form>
=======
    </x-filament-schemas::form>
>>>>>>> f548be94 (.)
=======
    </x-filament-schemas::form>
=======
    </form>
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
    
</x-filament-panels::page>
