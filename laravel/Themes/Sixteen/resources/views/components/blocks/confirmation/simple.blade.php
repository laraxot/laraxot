{{--
|--------------------------------------------------------------------------
| Confirmation Block - Simple
|--------------------------------------------------------------------------
|
| Usage:
|   <x-blocks.confirmation.simple
|       title="Appuntamento Confermato"
|       message="Il tuo appuntamento è stato confermato."
|       icon="check"
|   />
|
| Props:
|   - title: string - Titolo della conferma
|   - message: string - Messaggio di conferma
|   - icon: string - Tipo icona (check, info, warning, error)
|
| Icon Convention:
|   Use <x-filament::icon> for Filament icon rendering
|   Format: <x-filament::icon icon="heroicon-o-check-circle" class="..." />
|
| References:
|   - Flowbite: Alert components
|   - DaisyUI: Alert
|   - Bootstrap Italia: Alert
|   - Filament Icons: https://filamentphp.com/docs/3.x/forms/fields/icon-picker
|
--}}

@props([
    'title' => 'Operazione Completata',
    'message' => 'La tua richiesta è stata elaborata con successo.',
    'icon' => 'check',
])

<div class="bg-white rounded-lg shadow-lg p-8 max-w-lg mx-auto text-center" role="alert" aria-live="polite">
    {{-- Icon Container --}}
    <div class="inline-flex items-center justify-center w-16 h-16 rounded-full mb-4
        @if($icon === 'check') bg-green-100
        @elseif($icon === 'info') bg-blue-100
        @elseif($icon === 'warning') bg-yellow-100
        @elseif($icon === 'error') bg-red-100
        @else bg-gray-100
        @endif">
        
        {{-- Check Icon --}}
        @if($icon === 'check')
            <x-filament::icon icon="heroicon-o-check-circle" class="w-8 h-8 text-green-600" />
        
        {{-- Info Icon --}}
        @elseif($icon === 'info')
            <x-filament::icon icon="heroicon-o-information-circle" class="w-8 h-8 text-blue-600" />
        
        {{-- Warning Icon --}}
        @elseif($icon === 'warning')
            <x-filament::icon icon="heroicon-o-exclamation-triangle" class="w-8 h-8 text-yellow-600" />
        
        {{-- Error Icon --}}
        @elseif($icon === 'error')
            <x-filament::icon icon="heroicon-o-x-circle" class="w-8 h-8 text-red-600" />
        
        {{-- Default Icon --}}
        @else
            <x-filament::icon icon="heroicon-o-check-circle" class="w-8 h-8 text-gray-600" />
        @endif
    </div>
    
    {{-- Title --}}
    <h2 class="text-2xl font-bold text-gray-900 mb-2">
        {{ $title }}
    </h2>
    
    {{-- Message --}}
    <p class="text-gray-600">
        {{ $message }}
    </p>
</div>
