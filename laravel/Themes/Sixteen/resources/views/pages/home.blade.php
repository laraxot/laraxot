<?php

use function Laravel\Folio\name;

name('home');
?>

<x-pub_theme::layouts.app>
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
        <main class="container mx-auto px-4 py-12">
            <div class="text-center">
                <h1 class="text-5xl font-bold text-gray-900 mb-6">Benvenuti nel Comune</h1>
                <p class="text-xl text-gray-600 mb-8">Gestisci le segnalazioni e i servizi cittadini</p>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8 max-w-4xl mx-auto">
                    <a href="/segnalazioni" class="bg-white rounded-lg shadow-lg p-8 hover:shadow-xl transition-shadow">
                        <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900 mb-2">Segnalazioni</h3>
                        <p class="text-gray-600">Visualizza e gestisci le segnalazioni cittadine</p>
                    </a>
                    
                    <a href="/segnalazioni/create" class="bg-white rounded-lg shadow-lg p-8 hover:shadow-xl transition-shadow">
                        <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900 mb-2">Nuova Segnalazione</h3>
                        <p class="text-gray-600">Crea una nuova segnalazione</p>
                    </a>
                    
                    <a href="#" class="bg-white rounded-lg shadow-lg p-8 hover:shadow-xl transition-shadow">
                        <div class="w-16 h-16 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-purple-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M3 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900 mb-2">Servizi</h3>
                        <p class="text-gray-600">Accedi ai servizi comunali</p>
                    </a>
                </div>
            </div>
        </main>
    </div>
</x-pub_theme::layouts.app>