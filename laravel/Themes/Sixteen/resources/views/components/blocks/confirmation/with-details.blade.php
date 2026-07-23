{{--
|--------------------------------------------------------------------------
| Confirmation Block - With Details
|--------------------------------------------------------------------------
|
| Usage:
|   <x-blocks.confirmation.with-details
|       title="Appuntamento Confermato"
|       message="Il tuo appuntamento è stato confermato."
|       :details="[
|           'Data' => '30 Marzo 2026',
|           'Ora' => '10:00',
|           'Luogo' => 'Ufficio Anagrafe'
|       ]"
|   />
|
| Props:
|   - title: string - Titolo della conferma
|   - message: string - Messaggio di conferma
|   - details: array - Dettagli dell'appuntamento/operazione
|   - icon: string - Tipo icona (check, info, warning, error)
|
| Icon Convention:
|   Use <x-filament::icon> for Filament icon rendering
|   Format: <x-filament::icon icon="heroicon-o-check-circle" class="..." />
|
| References:
|   - Flowbite: Cards with content
|   - Tailwind Plus: Confirmation pages
|   - DaisyUI: Card
|   - Bootstrap Italia: Card, Alert
|   - Filament Icons: https://filamentphp.com/docs/3.x/forms/fields/icon-picker
|
--}}

@props([
    'title' => 'Appuntamento Confermato',
    'message' => 'Il tuo appuntamento è stato confermato.',
    'details' => [],
    'icon' => 'check',
])

<div class="bg-white rounded-lg shadow-lg p-8 max-w-2xl mx-auto" role="alert" aria-live="polite">
    {{-- Icon + Title Section --}}
    <div class="text-center mb-6">
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
                <x-filament::icon icon="heroicon-o-check-circle" class="w-8 h-8 text-green-600" aria-hidden="true" />
            
            {{-- Info Icon --}}
            @elseif($icon === 'info')
                <x-filament::icon icon="heroicon-o-information-circle" class="w-8 h-8 text-blue-600" aria-hidden="true" />
            
            {{-- Warning Icon --}}
            @elseif($icon === 'warning')
                <x-filament::icon icon="heroicon-o-exclamation-triangle" class="w-8 h-8 text-yellow-600" aria-hidden="true" />
            
            {{-- Error Icon --}}
            @elseif($icon === 'error')
                <x-filament::icon icon="heroicon-o-x-circle" class="w-8 h-8 text-red-600" aria-hidden="true" />
            
            {{-- Default Icon --}}
            @else
                <x-filament::icon icon="heroicon-o-check-circle" class="w-8 h-8 text-gray-600" aria-hidden="true" />
            @endif
        </div>
        
        {{-- Title --}}
        <h2 class="text-2xl font-bold text-gray-900">
            {{ $title }}
        </h2>
        
        {{-- Message --}}
        @if($message)
        <p class="text-gray-600 mt-2">
            {{ $message }}
        </p>
        @endif
    </div>
    
    {{-- Details Box --}}
    @if($details && count($details) > 0)
    <div class="bg-gray-50 rounded-lg p-6" aria-label="Dettagli dell'appuntamento">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">
            Dettagli Appuntamento
        </h3>
        <dl class="space-y-3">
            @foreach($details as $label => $value)
                <div class="flex justify-between items-start">
                    <dt class="text-gray-600 font-medium">
                        {{ $label }}
                    </dt>
                    <dd class="text-gray-900 font-semibold text-right ml-4">
                        {{ $value }}
                    </dd>
                </div>
            @endforeach
        </dl>
    </div>
    @endif
</div>
