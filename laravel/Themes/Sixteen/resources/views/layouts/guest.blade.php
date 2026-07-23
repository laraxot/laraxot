<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        
        <title>{{ config('app.name', 'Laravel') }}</title>
        
        <!-- Skip Links (AGID Compliant) - MOVED TO BODY -->
        @vite(['resources/css/app.css'], 'themes/Sixteen')
    </head>
    
    <body>
        <!-- AGID Header -->
        <header role="banner" class="bg-blue-600 text-white py-3">
            <div class="container mx-auto px-4">
                <div class="flex justify-between items-center">
                    <div class="flex items-center space-x-4">
                        <x-pub_theme::ui.logo class="h-8 w-auto text-white" />
                        <x-pub_theme::ui.logo class="h-8 w-auto text-white" />
                        <span class="font-semibold">{{ config('app.institution_name', 'Ente di appartenenza') }}</span>
                    </div>
                    @if(config('app.institution_url'))
                        <a href="{{ config('app.institution_url') }}" 
                           class="text-white hover:text-blue-200 transition-colors"
                           target="_blank" rel="noopener noreferrer">
                            Vai al sito dell'ente
                        </a>
                    @endif
                </div>
            </div>
        </header>
        
        <!-- Breadcrumb Navigation (AGID Compliant) -->
        <nav role="navigation" aria-label="Percorso di navigazione" class="bg-gray-50 py-3 border-b border-gray-200">
            <div class="container mx-auto px-4">
                <ol class="flex items-center space-x-2 text-sm">
                    <li>
                        <a href="{{ route('home') }}" class="text-blue-600 hover:text-blue-800 transition-colors">
                            <svg class="w-4 h-4 inline mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"></path>
                            </svg>
                            Home
                        </a>
                    </li>
                    <li class="text-gray-400" aria-hidden="true">/</li>
                    <li class="text-gray-700 font-medium" aria-current="page">
                        {{ $title ?? 'Pagina corrente' }}
                    </li>
                </ol>
            </div>
        </nav>
        
        <!-- Main Content -->
        <main id="main-content" role="main" class="min-h-screen bg-gray-50">
            <div class="container mx-auto px-4 py-12">
                <!-- Page Header -->
                <div class="text-center mb-8">
                    <div class="flex justify-center mb-6">
                        <x-pub_theme::ui.logo class="h-16 w-auto text-blue-600" />
                        <x-pub_theme::ui.logo class="h-16 w-auto text-blue-600" />
                    </div>
                    
                    <h1 class="text-3xl font-bold text-gray-900 mb-2">
                        {{ $title ?? config('app.name') }}
                    </h1>
                    
                    @if(isset($description))
                        <p class="text-gray-600">
                            {{ $description }}
                        </p>
                    @endif
                </div>
                
                <!-- Content Container -->
                <div id="login-form" 
                     class="bg-white rounded-lg shadow-lg border border-gray-200 overflow-hidden max-w-md mx-auto"
                     role="region" 
                     aria-labelledby="login-heading">
                    
                    <!-- Form Header -->
                    <div class="bg-blue-50 px-6 py-4 border-b border-gray-200">
                        <h2 id="login-heading" class="text-lg font-semibold text-gray-900">
                            Accedi con le tue credenziali
                        </h2>
                        <p class="text-sm text-gray-600 mt-1">
                            Utilizza le credenziali fornite dall'amministratore
                        </p>
                    </div>
                    
                    <!-- Form Body -->
                    <div class="px-6 py-6">
                        {{ $slot }}
                    </div>
                    
                    <!-- Form Footer -->
                    <div class="bg-gray-50 px-6 py-4 border-t border-gray-200">
                        <div class="text-sm text-gray-600 space-y-2">
                            <p class="flex items-center">
                                <svg class="w-4 h-4 mr-2 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                                </svg>
                                I tuoi dati sono protetti e crittografati
                            </p>
                            <p>
                                <a href="{{ route('pages.view', ['slug' => 'help']) }}" class="text-blue-600 hover:text-blue-800 underline">
                                    Hai bisogno di aiuto?
                                </a>
                            </p>
                        </div>
                    </div>
                </div>
                
                <!-- AGID Compliance Information -->
                <div class="mt-8 text-center text-sm text-gray-600 max-w-md mx-auto">
                    <p class="mb-2">
                        Questo servizio è conforme alle 
                        <a href="https://www.agid.gov.it/" 
                           target="_blank" 
                           rel="noopener noreferrer"
                           class="text-blue-600 hover:text-blue-800 underline">
                            linee guida AGID
                        </a>
                    </p>
                    <p class="mb-4">
                        Per informazioni sulla privacy consulta la 
                        <a href="{{ route('pages.view', ['slug' => 'privacy']) }}" 
                           class="text-blue-600 hover:text-blue-800 underline">
                            informativa sulla privacy
                        </a>
                    </p>
                    
                    <!-- Accessibility Information -->
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                        <p class="mb-2">
                            <strong>Accessibilità:</strong> Questo servizio è accessibile secondo le linee guida WCAG 2.1 AA.
                            <a href="{{ route('pages.view', ['slug' => 'accessibility']) }}" 
                               class="text-blue-600 hover:text-blue-800 underline">
                                Dichiarazione di accessibilità
                            </a>
                        </p>
                        <p class="text-xs">
                            <strong>Navigazione da tastiera:</strong> Usa Tab per navigare tra i campi e Invio per inviare il modulo.
                        </p>
                    </div>
                </div>
            </div>
        </main>
        
        <!-- AGID Footer -->
        <footer role="contentinfo" class="mt-12 bg-gray-900 text-white py-8">
            <div class="container mx-auto px-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div class="flex items-center space-x-4">
                        <x-pub_theme::ui.logo class="h-10 w-auto text-white" />
                        <x-pub_theme::ui.logo class="h-10 w-auto text-white" />
                        <div>
                            <h3 class="text-lg font-semibold">{{ config('app.name', 'Nome Ente') }}</h3>
                            <p class="text-sm opacity-80 mt-1">{{ config('app.tagline', 'Servizi digitali per i cittadini') }}</p>
                        </div>
                    </div>
                    
                    <div>
                        <h4 class="text-lg font-semibold mb-4">Link utili</h4>
                        <ul class="space-y-2 text-sm">
                            <li><a href="{{ route('pages.view', ['slug' => 'privacy']) }}" class="opacity-80 hover:opacity-100 transition-opacity">Privacy</a></li>
                            <li><a href="{{ route('pages.view', ['slug' => 'accessibility']) }}" class="opacity-80 hover:opacity-100 transition-opacity">Dichiarazione di accessibilità</a></li>
                            <li><a href="{{ route('pages.view', ['slug' => 'contacts']) }}" class="opacity-80 hover:opacity-100 transition-opacity">Contatti</a></li>
                            <li><a href="{{ route('pages.view', ['slug' => 'legal-notice']) }}" class="opacity-80 hover:opacity-100 transition-opacity">Note legali</a></li>
                        </ul>
                    </div>
                </div>
                
                <div class="mt-8 pt-8 border-t border-gray-700 text-center text-sm opacity-80">
                    <p>&copy; {{ date('Y') }} {{ config('app.name') }}. Tutti i diritti riservati.</p>
                </div>
            </div>
        </footer>
    </body>
</html>
