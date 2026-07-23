@props([
    'title' => 'I tuoi dati',
    'description' => 'Verifica i tuoi dati anagrafici',
    'user' => [],
])

@php
    $user = array_merge([
        'nome' => '',
        'cognome' => '',
        'codice_fiscale' => '',
        'email' => '',
        'telefono' => '',
    ], $user);
@endphp

<div class="step-content mt-8">
    @if($title)
        <h3 class="text-lg font-semibold text-gray-900 mb-4" data-element="step-title">{{ $title }}</h3>
    @endif
    
    @if($description)
        <p class="text-gray-600 mb-6" data-element="step-description">{{ $description }}</p>
    @endif

    <div class="bg-gray-50 rounded-lg p-6">
        <dl class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <dt class="text-sm text-gray-500">Nome</dt>
                <dd class="text-gray-900 font-medium" data-element="user-nome">{{ $user['nome'] ?: 'Mario' }}</dd>
            </div>
            <div>
                <dt class="text-sm text-gray-500">Cognome</dt>
                <dd class="text-gray-900 font-medium" data-element="user-cognome">{{ $user['cognome'] ?: 'Rossi' }}</dd>
            </div>
            <div>
                <dt class="text-sm text-gray-500">Codice Fiscale</dt>
                <dd class="text-gray-900 font-medium" data-element="user-cf">{{ $user['codice_fiscale'] ?: 'RSSMRA85T10A562B' }}</dd>
            </div>
            <div>
                <dt class="text-sm text-gray-500">Email</dt>
                <dd class="text-gray-900 font-medium" data-element="user-email">{{ $user['email'] ?: 'mario.rossi@email.it' }}</dd>
            </div>
            <div>
                <dt class="text-sm text-gray-500">Telefono</dt>
                <dd class="text-gray-900 font-medium" data-element="user-telefono">{{ $user['telefono'] ?: '333 1234567' }}</dd>
            </div>
        </dl>
        
        <div class="mt-4 pt-4 border-t border-gray-200">
            <a href="#" class="text-sm text-blue-600 hover:underline" data-element="edit-data">Modifica dati</a>
        </div>
    </div>

    <div class="mt-6 flex justify-between">
        <button 
            type="button"
            class="px-6 py-2 border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2"
            @click="currentStep--"
            data-element="step-prev"
        >
            Indietro
        </button>
        <button 
            type="button"
            class="px-6 py-2 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
            @click="currentStep++"
            data-element="step-next"
        >
            Continua
        </button>
    </div>
</div>