@props([
    'title' => 'Richiedi una Consulenza',
    'subtitle' => 'Compila il form e ti ricontatteremo entro 24 ore',
    'fields' => [],
    'submit_label' => 'Invia Richiesta',
    'privacy_text' => 'Inviando questo form accetti la nostra Privacy Policy.',
])

@php
    $submitLabel = $submit_label;
    $privacyText = $privacy_text;
@endphp

<section class="py-16 bg-white">
    <div class="container mx-auto px-4">
        {{-- Section Title --}}
        <div class="text-center mb-12">
            <h2 class="text-3xl font-bold text-gray-900 mb-4">{{ $title }}</h2>
            <p class="text-lg text-gray-600">{{ $subtitle }}</p>
        </div>

        {{-- Contact Form --}}
        <div class="max-w-2xl mx-auto">
            <form method="POST" action="/it/contact/submit" class="space-y-6">
                @csrf

                {{-- Name --}}
                @if($nameField = collect($fields)->firstWhere('name', 'nome'))
                    <div>
                        <label for="nome" class="block text-sm font-medium text-gray-700 mb-2">
                            {{ $nameField['label'] ?? 'Nome e Cognome' }}
                            @if($nameField['required'] ?? false)
                                <span class="text-red-500">*</span>
                            @endif
                        </label>
                        <input
                            type="{{ $nameField['type'] ?? 'text' }}"
                            name="nome"
                            id="nome"
                            autocomplete="name"
                            {{ $nameField['required'] ?? false ? 'required aria-required="true"' : '' }}
                            aria-describedby="nome-help"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#1E5A96] focus:border-transparent transition-colors"
                            placeholder="{{ $nameField['placeholder'] ?? 'Inserisci il tuo nome completo' }}"
                        >
                        <p id="nome-help" class="sr-only">Inserisci il tuo nome e cognome completo</p>
                    </div>
                @endif

                {{-- Email --}}
                @if($emailField = collect($fields)->firstWhere('name', 'email'))
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                            {{ $emailField['label'] ?? 'Email' }}
                            @if($emailField['required'] ?? false)
                                <span class="text-red-500">*</span>
                            @endif
                        </label>
                        <input
                            type="{{ $emailField['type'] ?? 'email' }}"
                            name="email"
                            id="email"
                            autocomplete="email"
                            {{ $emailField['required'] ?? false ? 'required aria-required="true"' : '' }}
                            aria-describedby="email-help"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#1E5A96] focus:border-transparent transition-colors"
                            placeholder="{{ $emailField['placeholder'] ?? 'esempio@email.com' }}"
                        >
                        <p id="email-help" class="sr-only">Inserisci il tuo indirizzo email</p>
                    </div>
                @endif

                {{-- Phone --}}
                @if($phoneField = collect($fields)->firstWhere('name', 'telefono'))
                    <div>
                        <label for="telefono" class="block text-sm font-medium text-gray-700 mb-2">
                            {{ $phoneField['label'] ?? 'Telefono' }}
                            @if($phoneField['required'] ?? false)
                                <span class="text-red-500">*</span>
                            @endif
                        </label>
                        <input
                            type="{{ $phoneField['type'] ?? 'tel' }}"
                            name="telefono"
                            id="telefono"
                            autocomplete="tel"
                            {{ $phoneField['required'] ?? false ? 'required aria-required="true"' : '' }}
                            aria-describedby="telefono-help"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#1E5A96] focus:border-transparent transition-colors"
                            placeholder="{{ $phoneField['placeholder'] ?? '+39 347 58 96 127' }}"
                        >
                        <p id="telefono-help" class="sr-only">Inserisci il tuo numero di telefono</p>
                    </div>
                @endif

                {{-- Studio Name --}}
                @if($studioField = collect($fields)->firstWhere('name', 'studio'))
                    <div>
                        <label for="studio" class="block text-sm font-medium text-gray-700 mb-2">
                            {{ $studioField['label'] ?? 'Nome Studio' }}
                            @if($studioField['required'] ?? false)
                                <span class="text-red-500">*</span>
                            @endif
                        </label>
                        <input
                            type="{{ $studioField['type'] ?? 'text' }}"
                            name="studio"
                            id="studio"
                            autocomplete="organization"
                            {{ $studioField['required'] ?? false ? 'required aria-required="true"' : '' }}
                            aria-describedby="studio-help"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#1E5A96] focus:border-transparent transition-colors"
                            placeholder="{{ $studioField['placeholder'] ?? 'Nome del tuo studio' }}"
                        >
                        <p id="studio-help" class="sr-only">Inserisci il nome del tuo studio professionale</p>
                    </div>
                @endif

                {{-- Studio Type --}}
                @if($tipoField = collect($fields)->firstWhere('name', 'tipo'))
                    <div>
                        <label for="tipo" class="block text-sm font-medium text-gray-700 mb-2">
                            {{ $tipoField['label'] ?? 'Tipo di Studio' }}
                            @if($tipoField['required'] ?? false)
                                <span class="text-red-500">*</span>
                            @endif
                        </label>
                        <select
                            name="tipo"
                            id="tipo"
                            autocomplete="organization-title"
                            {{ $tipoField['required'] ?? false ? 'required aria-required="true"' : '' }}
                            aria-describedby="tipo-help"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#1E5A96] focus:border-transparent transition-colors bg-white"
                        >
                            <option value="">Seleziona tipo di studio</option>
                            @foreach($tipoField['options'] ?? [] as $option)
                                <option value="{{ $option }}">{{ $option }}</option>
                            @endforeach
                        </select>
                        <p id="tipo-help" class="sr-only">Seleziona il tipo di studio professionale</p>
                    </div>
                @endif

                {{-- Message --}}
                @if($messageField = collect($fields)->firstWhere('name', 'messaggio'))
                    <div>
                        <label for="messaggio" class="block text-sm font-medium text-gray-700 mb-2">
                            {{ $messageField['label'] ?? 'Messaggio' }}
                            @if($messageField['required'] ?? false)
                                <span class="text-red-500">*</span>
                            @endif
                        </label>
                        <textarea
                            name="messaggio"
                            id="messaggio"
                            rows="5"
                            autocomplete="off"
                            {{ $messageField['required'] ?? false ? 'required aria-required="true"' : '' }}
                            aria-describedby="messaggio-help"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#1E5A96] focus:border-transparent transition-colors resize-none"
                            placeholder="{{ $messageField['placeholder'] ?? 'Descrivi la tua richiesta...' }}"
                        ></textarea>
                        <p id="messaggio-help" class="sr-only">Descrivi la tua richiesta di consulenza</p>
                    </div>
                @endif

                {{-- Privacy Checkbox --}}
                <div class="flex items-start">
                    <input
                        id="privacy"
                        name="privacy"
                        type="checkbox"
                        required
                        aria-required="true"
                        aria-describedby="privacy-help"
                        class="w-4 h-4 text-[#1E5A96] border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-[#1E5A96] mt-1"
                    >
                    <label for="privacy" class="ml-3 text-sm text-gray-600">
                        {{ $privacyText }}
                    </label>
                    <p id="privacy-help" class="sr-only">Devi accettare la privacy policy per inviare il form</p>
                </div>

                {{-- Submit Button --}}
                <div>
                    <button
                        type="submit"
                        aria-label="{{ $submitLabel }} - {{ __('Invia il form di contatto') }}"
                        class="w-full px-8 py-4 bg-[#1E5A96] text-white font-semibold rounded-lg hover:bg-[#164575] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#1E5A96] transition-all duration-200 transform hover:scale-[1.02]"
                    >
                        <span class="flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                            </svg>
                            {{ $submitLabel }}
                        </span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</section>