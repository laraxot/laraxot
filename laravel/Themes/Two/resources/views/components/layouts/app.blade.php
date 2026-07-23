<x-layouts.main>
    <x-slot name="beforeMain">
        <x-section slug="header"/>
    </x-slot>

    {{ $slot }}

    <x-slot name="afterMain">
        <x-section slug="footer"/>
    </x-slot>
</x-layouts.main>
