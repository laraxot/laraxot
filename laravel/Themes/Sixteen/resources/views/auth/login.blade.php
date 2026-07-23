<x-guest-layout>
    <x-authentication-card>
        <x-slot name="logo">
            <x-authentication-card-logo />
    <x-authentication-card>
        <x-slot name="logo">
            <x-authentication-card-logo />
        </x-slot>

        {{-- Integrazione LoginWidget Filament --}}
        @livewire(Modules\User\Filament\Widgets\LoginWidget::class)

    </x-authentication-card>
    </x-authentication-card>
</x-guest-layout>
