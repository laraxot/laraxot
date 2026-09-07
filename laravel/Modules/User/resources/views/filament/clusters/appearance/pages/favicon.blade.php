<?php

declare(strict_types=1);

?>
<x-filament-panels::page>

<<<<<<< HEAD
<<<<<<< HEAD
    <form wire:submit="updateData">
=======
    <x-filament-schemas::form wire:submit="updateData">
>>>>>>> f548be94 (.)
=======
    <x-filament-schemas::form wire:submit="updateData">
=======
    <form wire:submit="updateData">
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
        {{ $this->form }}

        <x-filament::actions
            :actions="$this->getUpdateFormActions()"
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
