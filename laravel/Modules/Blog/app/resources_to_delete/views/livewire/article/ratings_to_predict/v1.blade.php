<x-filament-panels::page>
    <form
         wire:submit="save"
         >
        {{ $this->form }}

        <x-filament::button type="submit" color="primary">
            {{ __('Save') }}
        </x-filament::button>

        <x-filament::button color="danger" wire:click="save" class="inline-flex justify-center rounded-md border border-transparent bg-green-700 py-2 px-4 text-sm font-medium text-white shadow-sm hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-700 focus:ring-offset-2">
            Add Rating
        </x-filament::button>

    </form>
</x-filament-panels::page>
