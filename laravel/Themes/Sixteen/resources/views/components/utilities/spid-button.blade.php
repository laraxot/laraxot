@props([
    'size' => 'md', // sm, md, lg
    'variant' => 'primary', // primary, secondary
    'providers' => [], // SPID identity providers
    'returnUrl' => null,
    'level' => 1, // SPID authentication level (1, 2, 3)
])

@php
$sizeClasses = [
    'sm' => 'px-4 py-2 text-sm',
    'md' => 'px-6 py-3 text-base',
    'lg' => 'px-8 py-4 text-lg',
];

$variantClasses = [
    'primary' => 'bg-italia-blue-500 hover:bg-italia-blue-600 text-white',
    'secondary' => 'bg-white hover:bg-gray-50 text-italia-blue-500 border border-italia-blue-500',
];

$defaultProviders = [
    'aruba' => ['name' => 'Aruba ID', 'logo' => '/assets/spid/aruba.svg'],
    'infocert' => ['name' => 'InfoCert ID', 'logo' => '/assets/spid/infocert.svg'],
    'poste' => ['name' => 'PosteID', 'logo' => '/assets/spid/poste.svg'],
    'tim' => ['name' => 'TIM ID', 'logo' => '/assets/spid/tim.svg'],
    'sielte' => ['name' => 'Sielte ID', 'logo' => '/assets/spid/sielte.svg'],
    'register' => ['name' => 'Register.it', 'logo' => '/assets/spid/register.svg'],
    'namirial' => ['name' => 'Namirial ID', 'logo' => '/assets/spid/namirial.svg'],
    'intesa' => ['name' => 'Intesa ID', 'logo' => '/assets/spid/intesa.svg'],
    'lepida' => ['name' => 'Lepida ID', 'logo' => '/assets/spid/lepida.svg'],
];

$spidProviders = empty($providers) ? $defaultProviders : $providers;
@endphp

<div class="spid-auth-container" x-data="{ open: false }">
    <!-- SPID Main Button -->
    <button 
        type="button"
        @click="open = !open"
        class="inline-flex items-center justify-center rounded-md font-medium transition-colors focus:outline-none focus:ring-2 focus:ring-italia-blue-500 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed {{ $sizeClasses[$size] }} {{ $variantClasses[$variant] }}"
        aria-haspopup="true"
        aria-expanded="false"
        x-bind:aria-expanded="open"
        {{ $attributes }}
    >
        <!-- SPID Logo -->
        <svg class="w-6 h-6 mr-2" viewBox="0 0 100 100" fill="currentColor">
            <path d="M50 15c19.33 0 35 15.67 35 35s-15.67 35-35 35-35-15.67-35-35 15.67-35 35-35zm0 5c-16.54 0-30 13.46-30 30s13.46 30 30 30 30-13.46 30-30-13.46-30-30-30z"/>
            <path d="M35 35h30v5H35zm0 10h30v5H35zm0 10h20v5H35z"/>
        </svg>
        
        <span>Entra con SPID</span>
        
        <!-- Dropdown Arrow -->
        <svg class="ml-2 -mr-1 h-4 w-4" x-bind:class="{ 'rotate-180': open }" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"/>
        </svg>
    </button>

    <!-- SPID Providers Dropdown -->
    <div 
        x-show="open"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95"
        @click.away="open = false"
        class="absolute z-50 mt-2 w-80 rounded-lg bg-white shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none"
        x-cloak
    >
        <div class="p-4">
            <div class="mb-4">
                <h3 class="text-lg font-semibold text-gray-900">Scegli il tuo Identity Provider</h3>
                <p class="text-sm text-gray-600 mt-1">Seleziona il provider con cui hai attivato SPID</p>
            </div>

            <div class="grid grid-cols-2 gap-3">
                @foreach($spidProviders as $key => $provider)
                <a 
                    href="{{ route('spid.login', ['provider' => $key, 'level' => $level, 'return_url' => $returnUrl]) }}"
                    class="flex items-center p-3 rounded-md border border-gray-200 hover:border-italia-blue-300 hover:bg-italia-blue-50 transition-colors group"
                >
                    <img 
                        src="{{ $provider['logo'] }}" 
                        alt="{{ $provider['name'] }} logo"
                        class="h-8 w-auto mr-3 object-contain"
                    >
                    <span class="text-sm font-medium text-gray-700 group-hover:text-italia-blue-700">
                        {{ $provider['name'] }}
                    </span>
                </a>
                @endforeach
            </div>

            <!-- SPID Info Links -->
            <div class="mt-4 pt-4 border-t border-gray-200">
                <div class="flex justify-between text-xs text-gray-500">
                    <a href="https://www.spid.gov.it/" 
                       target="_blank" 
                       rel="noopener"
                       class="hover:text-italia-blue-600">
                        Cos'è SPID?
                    </a>
                    <a href="https://www.spid.gov.it/richiedi-spid" 
                       target="_blank" 
                       rel="noopener"
                       class="hover:text-italia-blue-600">
                        Non hai SPID?
                    </a>
                </div>
                <div class="mt-2 text-xs text-gray-500">
                    Livello di sicurezza richiesto: {{ $level }}
                </div>
            </div>
        </div>
    </div>

    <!-- CIE Alternative Button -->
    <div class="mt-2">
        <a 
            href="{{ route('cie.login', ['return_url' => $returnUrl]) }}"
            class="inline-flex items-center justify-center w-full px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-italia-blue-500 focus:ring-offset-2"
        >
            <!-- CIE Icon -->
            <svg class="w-5 h-5 mr-2" viewBox="0 0 24 24" fill="currentColor">
                <path d="M20 4H4c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 14H4V6h16v12z"/>
                <path d="M6 8h12v2H6zm0 4h12v2H6z"/>
            </svg>
            Entra con CIE
        </a>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('spidAuth', () => ({
        open: false,
        
        init() {
            // Handle SPID authentication responses
            this.handleSpidResponse();
        },
        
        handleSpidResponse() {
            const urlParams = new URLSearchParams(window.location.search);
            const spidResponse = urlParams.get('spid_response');
            
            if (spidResponse === 'success') {
                this.showNotification('Autenticazione SPID completata con successo', 'success');
            } else if (spidResponse === 'error') {
                this.showNotification('Errore durante l\'autenticazione SPID', 'error');
            }
        },
        
        showNotification(message, type) {
            // Integration with notification system
            if (window.showNotification) {
                window.showNotification(message, type);
            }
        }
    }))
})
</script>
@endpush