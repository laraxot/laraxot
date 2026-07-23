@extends('sixteen::layouts.app')

@section('title', 'Elenco Segnalazioni')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Header Comunale -->
    <header class="bg-gradient-to-r from-blue-800 to-blue-900 text-white">
        <!-- Top Bar -->
        <div class="bg-blue-900 py-2">
            <div class="container mx-auto px-4 flex justify-between items-center text-sm">
                <div class="flex items-center space-x-4">
                    <span>Nome della Regione</span>
                </div>
                <div class="flex items-center space-x-4">
                    <button class="flex items-center space-x-1 hover:text-blue-200">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"></path>
                        </svg>
                        <span>Dark</span>
                    </button>
                    <button class="flex items-center space-x-1 hover:text-blue-200">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM9.555 7.168A1 1 0 008 8v4a1 1 0 001.555.832l3-2a1 1 0 000-1.664l-3-2z" clip-rule="evenodd"></path>
                        </svg>
                        <span>ITA</span>
                    </button>
                    <a href="#" class="flex items-center space-x-1 hover:text-blue-200">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path>
                        </svg>
                        <span>Accedi all'area personale</span>
                    </a>
                </div>
            </div>
        </div>

        <!-- Main Header -->
        <div class="py-4">
            <div class="container mx-auto px-4 flex justify-between items-center">
                <div class="flex items-center space-x-4">
                    <div class="flex items-center space-x-2">
                        <div class="w-12 h-12 bg-white rounded border-2 border-purple-500 flex items-center justify-center">
                            <span class="text-blue-800 font-bold text-lg">it</span>
                        </div>
                        <div>
                            <h1 class="text-2xl font-bold">Il mio Comune</h1>
                            <p class="text-sm text-blue-200">Un comune da vivere</p>
                        </div>
                    </div>
                </div>
                <div class="flex items-center space-x-6">
                    <div class="flex items-center space-x-2">
                        <span class="text-sm">Seguici su</span>
                        <div class="flex space-x-2">
                            <a href="#" class="w-8 h-8 bg-white bg-opacity-20 rounded flex items-center justify-center hover:bg-opacity-30">
                                <span class="text-xs font-bold">X</span>
                            </a>
                            <a href="#" class="w-8 h-8 bg-white bg-opacity-20 rounded flex items-center justify-center hover:bg-opacity-30">
                                <span class="text-xs font-bold">f</span>
                            </a>
                            <a href="#" class="w-8 h-8 bg-white bg-opacity-20 rounded flex items-center justify-center hover:bg-opacity-30">
                                <span class="text-xs font-bold">YT</span>
                            </a>
                            <a href="#" class="w-8 h-8 bg-white bg-opacity-20 rounded flex items-center justify-center hover:bg-opacity-30">
                                <span class="text-xs font-bold">IG</span>
                            </a>
                            <a href="#" class="w-8 h-8 bg-white bg-opacity-20 rounded flex items-center justify-center hover:bg-opacity-30">
                                <span class="text-xs font-bold">in</span>
                            </a>
                        </div>
                    </div>
                    <div class="flex items-center space-x-2">
                        <input type="text" placeholder="Cerca..." class="px-3 py-2 rounded text-gray-800 text-sm w-64">
                        <button class="w-8 h-8 bg-white bg-opacity-20 rounded flex items-center justify-center hover:bg-opacity-30">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd"></path>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
                </div>

        <!-- Navigation -->
        <nav class="bg-blue-700">
            <div class="container mx-auto px-4">
                <div class="flex space-x-8 py-3">
                    <a href="#" class="text-white hover:text-blue-200 font-medium">Amministrazione</a>
                    <a href="#" class="text-white hover:text-blue-200 font-medium">Novità</a>
                    <a href="#" class="text-white hover:text-blue-200 font-medium">Servizi</a>
                    <a href="#" class="text-white hover:text-blue-200 font-medium">Vivere il Comune</a>
                </div>
                <div class="flex space-x-6 py-2 border-t border-blue-600">
                    <a href="#" class="text-white hover:text-blue-200 text-sm">Iscrizioni</a>
                    <a href="#" class="text-white hover:text-blue-200 text-sm">Estate in città</a>
                    <a href="#" class="text-white hover:text-blue-200 text-sm">Polizia locale</a>
                    <a href="#" class="text-white hover:text-blue-200 text-sm">Tutti gli argomenti ></a>
                </div>
            </div>
        </nav>
    </header>

    <!-- Main Content -->
    <main class="container mx-auto px-4 py-8">
        <!-- Breadcrumbs -->
        <nav class="mb-6">
            <ol class="flex items-center space-x-2 text-sm text-gray-600">
                <li><a href="#" class="hover:text-blue-600">Home</a></li>
                <li class="flex items-center">
                    <svg class="w-4 h-4 mx-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                    </svg>
                    <span class="text-gray-900 font-medium">Elenco segnalazioni</span>
                </li>
            </ol>
        </nav>

        <!-- Page Title -->
        <div class="mb-8">
            <h1 class="text-4xl font-bold text-gray-900 mb-4">Elenco segnalazioni</h1>
            <p class="text-lg text-gray-600">Negli ultimi 12 mesi sono state risolte <span class="font-semibold text-green-600">73</span> segnalazioni.</p>
                </div>

        <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
            <!-- Left Sidebar - Categories -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-lg shadow-sm border p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">CATEGORIA</h3>
                    <div class="space-y-3">
                        <label class="flex items-center space-x-3 cursor-pointer hover:bg-gray-50 p-2 rounded">
                            <input type="checkbox" class="w-4 h-4 text-blue-600 rounded border-gray-300">
                            <span class="text-sm text-gray-700">Acqua, allagamenti, problemi fognari <span class="text-blue-600 font-medium">(21)</span></span>
                        </label>
                        <label class="flex items-center space-x-3 cursor-pointer hover:bg-gray-50 p-2 rounded">
                            <input type="checkbox" class="w-4 h-4 text-blue-600 rounded border-gray-300">
                            <span class="text-sm text-gray-700">Ambiente, inquinamento, protezione ambientale <span class="text-blue-600 font-medium">(14)</span></span>
                        </label>
                        <label class="flex items-center space-x-3 cursor-pointer hover:bg-gray-50 p-2 rounded">
                            <input type="checkbox" class="w-4 h-4 text-blue-600 rounded border-gray-300">
                            <span class="text-sm text-gray-700">Arredo urbano <span class="text-blue-600 font-medium">(7)</span></span>
                        </label>
                        <label class="flex items-center space-x-3 cursor-pointer hover:bg-gray-50 p-2 rounded">
                            <input type="checkbox" class="w-4 h-4 text-blue-600 rounded border-gray-300">
                            <span class="text-sm text-gray-700">Disinfestazione, derattizzazione, animali randagi <span class="text-blue-600 font-medium">(208)</span></span>
                        </label>
                        <label class="flex items-center space-x-3 cursor-pointer hover:bg-gray-50 p-2 rounded">
                            <input type="checkbox" class="w-4 h-4 text-blue-600 rounded border-gray-300">
                            <span class="text-sm text-gray-700">Igiene urbana, rifiuti, pulizia e decoro <span class="text-blue-600 font-medium">(321)</span></span>
                        </label>
                        <label class="flex items-center space-x-3 cursor-pointer hover:bg-gray-50 p-2 rounded">
                            <input type="checkbox" class="w-4 h-4 text-blue-600 rounded border-gray-300">
                            <span class="text-sm text-gray-700">Manutenzione immobili, edifici pubblici, scuole, barriere architettoniche, cimiteri <span class="text-blue-600 font-medium">(302)</span></span>
                        </label>
                        <label class="flex items-center space-x-3 cursor-pointer hover:bg-gray-50 p-2 rounded">
                            <input type="checkbox" class="w-4 h-4 text-blue-600 rounded border-gray-300">
                            <span class="text-sm text-gray-700">Ordine pubblico, disturbo della quiete <span class="text-blue-600 font-medium">(302)</span></span>
                        </label>
                        <label class="flex items-center space-x-3 cursor-pointer hover:bg-gray-50 p-2 rounded">
                            <input type="checkbox" class="w-4 h-4 text-blue-600 rounded border-gray-300">
                            <span class="text-sm text-gray-700">Parchi e verde pubblico <span class="text-blue-600 font-medium">(302)</span></span>
                        </label>
                        <label class="flex items-center space-x-3 cursor-pointer hover:bg-gray-50 p-2 rounded">
                            <input type="checkbox" class="w-4 h-4 text-blue-600 rounded border-gray-300">
                            <span class="text-sm text-gray-700">Servizi del comune <span class="text-blue-600 font-medium">(302)</span></span>
                        </label>
                        <label class="flex items-center space-x-3 cursor-pointer hover:bg-gray-50 p-2 rounded">
                            <input type="checkbox" class="w-4 h-4 text-blue-600 rounded border-gray-300">
                            <span class="text-sm text-gray-700">Sicurezza, degrado urbano e sociale <span class="text-blue-600 font-medium">(302)</span></span>
                        </label>
                        <label class="flex items-center space-x-3 cursor-pointer hover:bg-gray-50 p-2 rounded">
                            <input type="checkbox" class="w-4 h-4 text-blue-600 rounded border-gray-300">
                            <span class="text-sm text-gray-700">Strade, marciapiedi, segnaletica e viabilità <span class="text-blue-600 font-medium">(302)</span></span>
                        </label>
                    </div>
                </div>
            </div>

            <!-- Right Content - Map/List -->
            <div class="lg:col-span-3">
                <div class="bg-white rounded-lg shadow-sm border">
                    <!-- Results Header -->
                    <div class="p-6 border-b border-gray-200">
                        <div class="flex justify-between items-center">
                            <div class="flex items-center space-x-4">
                                <h2 class="text-xl font-semibold text-gray-900">645 Risultati</h2>
                                <div class="flex space-x-1">
                                    <button class="px-4 py-2 bg-green-600 text-white rounded-l-md font-medium">Mappa</button>
                                    <button class="px-4 py-2 bg-gray-100 text-gray-700 rounded-r-md font-medium hover:bg-gray-200">Elenco</button>
                                </div>
                            </div>
                            <a href="#" class="text-sm text-blue-600 hover:text-blue-800">Rimuovi tutti i filtri</a>
                        </div>
                    </div>

                    <!-- Map Container -->
                    <div class="h-96 bg-gray-100 flex items-center justify-center">
                        <div class="text-center">
                            <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"></path>
                            </svg>
                            <p class="text-gray-500">Mappa interattiva delle segnalazioni</p>
                            <p class="text-sm text-gray-400 mt-2">Firenze, Italia</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>
@endsection