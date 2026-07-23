{{-- Bootstrap Italia PagoPA Payment Button Component --}}
{{-- 
    Componente pulsante PagoPA conforme alle specifiche PA
    Supporta pagamenti online tramite PagoPA con branding ufficiale
--}}
@props([
    'amount' => 0,
    'description' => '',
    'paymentId' => null,
    'noticeNumber' => null,
    'fiscalCode' => null,
    'creditorReferenceId' => null,
    'size' => 'md', // 'sm', 'md', 'lg'
    'variant' => 'primary', // 'primary', 'secondary', 'outline'
    'showLogo' => true,
    'showAmount' => true,
    'disabled' => false,
    'loading' => false,
    'returnUrl' => null,
    'cancelUrl' => null,
    'environment' => 'production', // 'sandbox', 'production'
    'language' => 'it', // 'it', 'en'
])

@php
$sizeClasses = [
    'sm' => 'px-4 py-2 text-sm',
    'md' => 'px-6 py-3 text-base',
    'lg' => 'px-8 py-4 text-lg',
];

$variantClasses = [
    'primary' => 'bg-pagopa-blue hover:bg-pagopa-blue-dark text-white border-pagopa-blue focus:ring-pagopa-blue',
    'secondary' => 'bg-white hover:bg-gray-50 text-pagopa-blue border-pagopa-blue focus:ring-pagopa-blue',
    'outline' => 'bg-transparent hover:bg-pagopa-blue hover:text-white text-pagopa-blue border-pagopa-blue focus:ring-pagopa-blue',
];

$formattedAmount = number_format($amount / 100, 2, ',', '.'); // Converti centesimi in euro
$paymentUrl = route('pagopa.payment', [
    'amount' => $amount,
    'description' => $description,
    'payment_id' => $paymentId,
    'notice_number' => $noticeNumber,
    'fiscal_code' => $fiscalCode,
    'creditor_reference_id' => $creditorReferenceId,
    'return_url' => $returnUrl,
    'cancel_url' => $cancelUrl,
    'environment' => $environment,
]);
@endphp

<div class="pagopa-payment-container" x-data="{ 
    loading: @js($loading),
    processing: false,
    
    async initPayment() {
        if (this.processing || @js($disabled)) return;
        
        this.processing = true;
        
        try {
            // Emetti evento pre-pagamento
            this.$dispatch('pagopa-payment-init', {
                amount: {{ $amount }},
                description: '{{ $description }}',
                paymentId: '{{ $paymentId }}'
            });
            
            // Se è configurato un handler personalizzato, lo usa
            if (window.pagopaPaymentHandler) {
                await window.pagopaPaymentHandler({
                    amount: {{ $amount }},
                    description: '{{ $description }}',
                    paymentId: '{{ $paymentId }}',
                    noticeNumber: '{{ $noticeNumber }}',
                    fiscalCode: '{{ $fiscalCode }}',
                    creditorReferenceId: '{{ $creditorReferenceId }}',
                    returnUrl: '{{ $returnUrl }}',
                    cancelUrl: '{{ $cancelUrl }}',
                    environment: '{{ $environment }}'
                });
            } else {
                // Default: reindirizza alla pagina di pagamento
                window.location.href = '{{ $paymentUrl }}';
            }
        } catch (error) {
            console.error('Errore inizializzazione pagamento PagoPA:', error);
            this.$dispatch('pagopa-payment-error', { error });
        } finally {
            this.processing = false;
        }
    }
}">
    <button
        type="button"
        @click="initPayment()"
        :disabled="processing || @js($disabled)"
        class="inline-flex items-center justify-center rounded-md font-semibold transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed {{ $sizeClasses[$size] }} {{ $variantClasses[$variant] }}"
        :class="{ 'animate-pulse': processing }"
        aria-describedby="pagopa-info"
        {{ $attributes }}
    >
        {{-- Logo PagoPA --}}
        @if($showLogo)
            <div class="flex items-center mr-3">
                <svg class="w-6 h-6" viewBox="0 0 120 40" fill="currentColor">
                    {{-- Logo PagoPA ufficiale --}}
                    <path d="M15.5 8.5h4.8c3.2 0 5.7 2.5 5.7 5.7s-2.5 5.7-5.7 5.7h-4.8V8.5zm4.8 8.9c1.8 0 3.2-1.4 3.2-3.2s-1.4-3.2-3.2-3.2h-2.3v6.4h2.3z"/>
                    <path d="M35.2 8.5c3.5 0 6.4 2.9 6.4 6.4s-2.9 6.4-6.4 6.4-6.4-2.9-6.4-6.4 2.9-6.4 6.4-6.4zm0 10.3c2.1 0 3.9-1.7 3.9-3.9s-1.7-3.9-3.9-3.9-3.9 1.7-3.9 3.9 1.7 3.9 3.9 3.9z"/>
                    <path d="M51.5 8.5v2.5h-3.9v10.9h-2.5V11h-3.9V8.5h10.3z"/>
                    <path d="M58.9 8.5c3.5 0 6.4 2.9 6.4 6.4s-2.9 6.4-6.4 6.4-6.4-2.9-6.4-6.4 2.9-6.4 6.4-6.4zm0 10.3c2.1 0 3.9-1.7 3.9-3.9s-1.7-3.9-3.9-3.9-3.9 1.7-3.9 3.9 1.7 3.9 3.9 3.9z"/>
                    <path d="M75 8.5c3.2 0 5.7 2.5 5.7 5.7 0 2.1-1.1 3.9-2.8 4.9l3.2 2.8h-3.2l-2.9-2.6h-2.5v2.6h-2.5V8.5H75zm0 8.9c1.8 0 3.2-1.4 3.2-3.2s-1.4-3.2-3.2-3.2h-2.5v6.4H75z"/>
                    <path d="M90.4 8.5h2.5l6.4 12.9h-2.8l-1.3-2.6h-6.8l-1.3 2.6h-2.8l6.1-12.9zm3.2 7.8l-2.2-4.4-2.2 4.4h4.4z"/>
                    <path d="M108.2 8.5h4.8c3.2 0 5.7 2.5 5.7 5.7s-2.5 5.7-5.7 5.7h-4.8V8.5zm4.8 8.9c1.8 0 3.2-1.4 3.2-3.2s-1.4-3.2-3.2-3.2h-2.3v6.4h2.3z"/>
                </svg>
            </div>
        @endif

        {{-- Testo e icone --}}
        <span class="flex items-center">
            <template x-if="!processing">
                <span class="flex items-center">
                    @if($language === 'en')
                        Pay
                    @else
                        Paga
                    @endif
                    
                    @if($showAmount && $amount > 0)
                        <span class="mx-1">€</span>
                        <span class="font-bold">{{ $formattedAmount }}</span>
                    @endif
                    
                    @if($language === 'en')
                        <span class="ml-1">with PagoPA</span>
                    @else
                        <span class="ml-1">con PagoPA</span>
                    @endif
                </span>
            </template>
            
            <template x-if="processing">
                <span class="flex items-center">
                    <svg class="w-5 h-5 mr-2 animate-spin" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    @if($language === 'en')
                        Processing...
                    @else
                        Elaborazione...
                    @endif
                </span>
            </template>
        </span>

        {{-- Freccia --}}
        <svg class="w-5 h-5 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
        </svg>
    </button>

    {{-- Informazioni PagoPA --}}
    <div id="pagopa-info" class="mt-2 text-xs text-gray-600">
        @if($language === 'en')
            <p>Secure payments powered by PagoPA</p>
            @if($description)
                <p class="mt-1">{{ $description }}</p>
            @endif
        @else
            <p>Pagamenti sicuri tramite il circuito PagoPA</p>
            @if($description)
                <p class="mt-1">{{ $description }}</p>
            @endif
        @endif
        
        @if($noticeNumber)
            <p class="mt-1">
                <span class="font-medium">Numero avviso:</span> {{ $noticeNumber }}
            </p>
        @endif
    </div>

    {{-- Metodi di pagamento supportati --}}
    <div class="mt-3 flex items-center space-x-2 text-xs text-gray-500">
        <span>
            @if($language === 'en')
                Accepted:
            @else
                Accettati:
            @endif
        </span>
        
        {{-- Icone carte/metodi --}}
        <div class="flex items-center space-x-1">
            {{-- Visa --}}
            <svg class="w-6 h-4" viewBox="0 0 24 16" fill="none">
                <rect width="24" height="16" rx="2" fill="#1A1F71"/>
                <text x="12" y="11" text-anchor="middle" fill="white" font-size="8" font-weight="bold">VISA</text>
            </svg>
            
            {{-- Mastercard --}}
            <svg class="w-6 h-4" viewBox="0 0 24 16" fill="none">
                <rect width="24" height="16" rx="2" fill="#EB001B"/>
                <circle cx="10" cy="8" r="4" fill="#FF5F00"/>
                <circle cx="14" cy="8" r="4" fill="#F79E1B"/>
            </svg>
            
            {{-- PostePay --}}
            <svg class="w-6 h-4" viewBox="0 0 24 16" fill="none">
                <rect width="24" height="16" rx="2" fill="#FFD320"/>
                <text x="12" y="11" text-anchor="middle" fill="black" font-size="6" font-weight="bold">PostePay</text>
            </svg>
            
            {{-- Bonifico --}}
            <svg class="w-6 h-4" viewBox="0 0 24 16" fill="none">
                <rect width="24" height="16" rx="2" fill="#2E7D32"/>
                <text x="12" y="8" text-anchor="middle" fill="white" font-size="5">€</text>
                <text x="12" y="14" text-anchor="middle" fill="white" font-size="4">SEPA</text>
            </svg>
        </div>
    </div>
</div>

@push('styles')
<style>
.pagopa-blue { background-color: #0066cc; }
.pagopa-blue-dark { background-color: #0052a3; }
.border-pagopa-blue { border-color: #0066cc; }
.text-pagopa-blue { color: #0066cc; }
.focus\:ring-pagopa-blue:focus { --tw-ring-color: #0066cc; }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('alpine:init', () => {
    // Event listeners per PagoPA
    document.addEventListener('pagopa-payment-init', function(event) {
        console.log('Inizializzazione pagamento PagoPA:', event.detail);
        
        // Tracciamento analytics (se presente)
        if (window.gtag) {
            window.gtag('event', 'pagopa_payment_init', {
                'value': event.detail.amount / 100,
                'currency': 'EUR',
                'payment_id': event.detail.paymentId
            });
        }
    });
    
    document.addEventListener('pagopa-payment-error', function(event) {
        console.error('Errore pagamento PagoPA:', event.detail.error);
        
        // Mostra notifica errore (se presente)
        if (window.showNotification) {
            window.showNotification('Si è verificato un errore durante l\'inizializzazione del pagamento. Riprova.', 'error');
        }
        
        // Tracciamento errori
        if (window.gtag) {
            window.gtag('event', 'exception', {
                'description': 'PagoPA payment error',
                'fatal': false
            });
        }
    });
});

// Handler personalizzato per i pagamenti (opzionale)
// window.pagopaPaymentHandler = async function(paymentData) {
//     // Implementazione personalizzata
//     // Es: apertura modal, integrazione con API custom, etc.
// };
</script>
@endpush

{{-- 
Utilizzo:

<!-- Pulsante PagoPA base -->
<x-pagopa-button 
    :amount="5000"
    description="Pagamento tassa comunale"
    payment-id="PAY123456"
    notice-number="302000100000019421"
    fiscal-code="12345678901"
/>

<!-- Pulsante personalizzato -->
<x-pagopa-button 
    :amount="15000"
    description="Multa traffico"
    variant="outline"
    size="lg"
    :show-amount="true"
    notice-number="302000100000019421"
    fiscal-code="80087670016"
    creditor-reference-id="RF23567890123456"
    :return-url="route('payment.success')"
    :cancel-url="route('payment.cancel')"
/>

<!-- Pulsante disabilitato -->
<x-pagopa-button 
    :amount="0"
    description="Importo non disponibile"
    disabled
/>

<!-- Pulsante in caricamento -->
<x-pagopa-button 
    :amount="2500"
    description="Elaborazione in corso"
    loading
/>

<!-- Versione inglese -->
<x-pagopa-button 
    :amount="10000"
    description="Municipal tax payment"
    language="en"
    notice-number="302000100000019421"
/>

<!-- Con handler personalizzato -->
<script>
window.pagopaPaymentHandler = async function(paymentData) {
    // Apri modal personalizzato
    const modal = document.getElementById('payment-modal');
    modal.style.display = 'block';
    
    // Integrazione con API custom
    const response = await fetch('/api/pagopa/init', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(paymentData)
    });
    
    const result = await response.json();
    
    if (result.redirectUrl) {
        window.location.href = result.redirectUrl;
    }
};
</script>

Route necessarie (routes/web.php):
Route::post('/pagopa/payment', [PagopaController::class, 'initPayment'])->name('pagopa.payment');
Route::get('/pagopa/success', [PagopaController::class, 'success'])->name('payment.success');
Route::get('/pagopa/cancel', [PagopaController::class, 'cancel'])->name('payment.cancel');

Controller di esempio:
class PagopaController extends Controller
{
    public function initPayment(Request $request)
    {
        // Validazione dati
        $validated = $request->validate([
            'amount' => 'required|integer|min:1',
            'description' => 'required|string',
            'notice_number' => 'required|string',
            'fiscal_code' => 'required|string',
        ]);
        
        // Integrazione con PagoPA API
        $pagopaService = app(PagopaService::class);
        $paymentUrl = $pagopaService->createPayment($validated);
        
        return redirect($paymentUrl);
    }
}

Accessibilità:
- ARIA labels appropriati
- Navigazione da tastiera
- Screen reader friendly
- Messaggi di stato chiari
- Focus management

Sicurezza:
- Validazione parametri
- Token CSRF
- Crittografia dati sensibili
- Log audit pagamenti
--}}