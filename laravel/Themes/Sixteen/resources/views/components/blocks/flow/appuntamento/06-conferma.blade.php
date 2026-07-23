@props([
    'title' => 'Appuntamento confermato',
    'appointment' => [],
    'showActions' => true,
])

@php
    $appointment = array_merge([
        'office' => '',
        'date' => '',
        'time' => '',
        'motivo' => '',
        'codice_prenotazione' => '',
    ], $appointment);
@endphp

<div class="step-content mt-8">
    @if($title)
        <h3 class="text-lg font-semibold text-gray-900 mb-4" data-element="step-title">{{ $title }}</h3>
    @endif

    <div class="bg-green-50 border border-green-200 rounded-lg p-6 mb-6">
        <div class="flex items-center mb-4">
            <div class="flex-shrink-0">
                <svg class="w-12 h-12 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                </svg>
            </div>
            <div class="ml-4">
                <h4 class="text-lg font-semibold text-green-800">Appuntamento prenotato con successo!</h4>
                <p class="text-green-700">Riceverai una conferma via email</p>
            </div>
        </div>

        <div class="border-t border-green-200 pt-4 mt-4">
            <dl class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <dt class="text-sm text-green-700">Codice prenotazione</dt>
                    <dd class="text-lg font-bold text-green-800" data-element="booking-code">{{ $appointment['codice_prenotazione'] ?: 'APT-2025-001234' }}</dd>
                </div>
                <div>
                    <dt class="text-sm text-green-700">Ufficio</dt>
                    <dd class="text-green-800" data-element="confirmed-office">{{ $appointment['office'] ?: 'Ufficio Anagrafe' }}</dd>
                </div>
                <div>
                    <dt class="text-sm text-green-700">Data</dt>
                    <dd class="text-green-800" data-element="confirmed-date">{{ $appointment['date'] ?: '15 Aprile 2025' }}</dd>
                </div>
                <div>
                    <dt class="text-sm text-green-700">Orario</dt>
                    <dd class="text-green-800" data-element="confirmed-time">{{ $appointment['time'] ?: '09:00' }}</dd>
                </div>
                <div class="md:col-span-2">
                    <dt class="text-sm text-green-700">Motivo</dt>
                    <dd class="text-green-800" data-element="confirmed-motivo">{{ $appointment['motivo'] ?: 'Informazioni generali' }}</dd>
                </div>
            </dl>
        </div>
    </div>

    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
        <div class="flex">
            <svg class="w-5 h-5 text-blue-600 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
            </svg>
            <div class="ml-3">
                <p class="text-sm text-blue-700">
                    <strong>Ricorda:</strong> Presentati presso l'ufficio 10 minuti prima dell'orario prenotato e porta un documento di identità valido.
                </p>
            </div>
        </div>
    </div>

    @if($showActions)
    <div class="flex flex-wrap gap-4 justify-center">
        <a href="#" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
            </svg>
            Aggiungi al calendario
        </a>
        <a href="#" class="inline-flex items-center px-4 py-2 border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
            </svg>
            Stampa conferma
        </a>
    </div>
    @endif
</div>