<?php

use function Laravel\Folio\name;

name('segnalazioni');
?>

@volt
new class extends \Livewire\Volt\Component {
    public $search = '';
    public $selectedCategories = [];
    public $viewMode = 'map'; // 'map' or 'list'
    public $tickets = [];
    public $categories = [
        'acqua' => ['name' => 'Acqua, allagamenti, problemi fognari', 'count' => 21],
        'ambiente' => ['name' => 'Ambiente, inquinamento, protezione ambientale', 'count' => 14],
        'arredo' => ['name' => 'Arredo urbano', 'count' => 7],
        'disinfestazione' => ['name' => 'Disinfestazione, derattizzazione, animali randagi', 'count' => 208],
        'igiene' => ['name' => 'Igiene urbana, rifiuti, pulizia e decoro', 'count' => 321],
        'manutenzione' => ['name' => 'Manutenzione immobili, edifici pubblici, scuole, barriere architettoniche, cimiteri', 'count' => 302],
        'ordine' => ['name' => 'Ordine pubblico, disturbo della quiete', 'count' => 302],
        'parchi' => ['name' => 'Parchi e verde pubblico', 'count' => 302],
        'servizi' => ['name' => 'Servizi del comune', 'count' => 302],
        'sicurezza' => ['name' => 'Sicurezza, degrado urbano e sociale', 'count' => 302],
        'strade' => ['name' => 'Strade, marciapiedi, segnaletica e viabilità', 'count' => 302],
    ];

    public function mount()
    {
        // Simuliamo alcuni dati per la demo
        $this->tickets = [
            ['id' => 1, 'title' => 'Buca sulla strada principale', 'category' => 'strade', 'status' => 'open', 'lat' => 43.7696, 'lng' => 11.2558],
            ['id' => 2, 'title' => 'Lampione non funzionante', 'category' => 'arredo', 'status' => 'in_progress', 'lat' => 43.7719, 'lng' => 11.2481],
            ['id' => 3, 'title' => 'Rifiuti abbandonati', 'category' => 'igiene', 'status' => 'resolved', 'lat' => 43.7750, 'lng' => 11.2500],
        ];
    }

    public function toggleCategory($category)
    {
        if (in_array($category, $this->selectedCategories)) {
            $this->selectedCategories = array_diff($this->selectedCategories, [$category]);
        } else {
            $this->selectedCategories[] = $category;
        }
    }

    public function setViewMode($mode)
    {
        $this->viewMode = $mode;
    }

    public function clearFilters()
    {
        $this->selectedCategories = [];
        $this->search = '';
    }

    public function getFilteredTickets()
    {
        $filtered = $this->tickets;

        if (!empty($this->selectedCategories)) {
            $filtered = array_filter($filtered, function($ticket) {
                return in_array($ticket['category'], $this->selectedCategories);
            });
        }

        if (!empty($this->search)) {
            $filtered = array_filter($filtered, function($ticket) {
                return stripos($ticket['title'], $this->search) !== false;
            });
        }

        return $filtered;
    }

    public function getTotalResults()
    {
        return count($this->getFilteredTickets());
    }
}
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
        <main class="container mx-auto px-4 py-8">
            <!-- Breadcrumbs -->
            <nav class="mb-6">
                <ol class="flex items-center space-x-2 text-sm text-gray-600">
                    <li><a href="/" class="hover:text-blue-600">Home</a></li>
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
                            @foreach($categories as $key => $category)
                            <label class="flex items-center space-x-3 cursor-pointer hover:bg-gray-50 p-2 rounded">
                                <input type="checkbox" 
                                       wire:model.live="selectedCategories" 
                                       value="{{ $key }}"
                                       class="w-4 h-4 text-blue-600 rounded border-gray-300">
                                <span class="text-sm text-gray-700">
                                    {{ $category['name'] }} 
                                    <span class="text-blue-600 font-medium">({{ $category['count'] }})</span>
                                </span>
                            </label>
                            @endforeach
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
                                    <h2 class="text-xl font-semibold text-gray-900">{{ $this->getTotalResults() }} Risultati</h2>
                                    <div class="flex space-x-1">
                                        <button wire:click="setViewMode('map')" 
                                                class="px-4 py-2 {{ $viewMode === 'map' ? 'bg-green-600 text-white' : 'bg-gray-100 text-gray-700' }} rounded-l-md font-medium">
                                            Mappa
                                        </button>
                                        <button wire:click="setViewMode('list')" 
                                                class="px-4 py-2 {{ $viewMode === 'list' ? 'bg-green-600 text-white' : 'bg-gray-100 text-gray-700' }} rounded-r-md font-medium">
                                            Elenco
                                        </button>
                                    </div>
                                </div>
                                <button wire:click="clearFilters" class="text-sm text-blue-600 hover:text-blue-800">
                                    Rimuovi tutti i filtri
                                </button>
                            </div>
                        </div>

                        <!-- Map/List Container -->
                        @if($viewMode === 'map')
                        <div class="h-96 bg-gray-100 flex items-center justify-center">
                            <div class="text-center">
                                <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"></path>
                                </svg>
                                <p class="text-gray-500">Mappa interattiva delle segnalazioni</p>
                                <p class="text-sm text-gray-400 mt-2">Firenze, Italia</p>
                                <div class="mt-4 text-xs text-gray-400">
                                    @foreach($this->getFilteredTickets() as $ticket)
                                        <div class="inline-block mr-2 mb-1 px-2 py-1 bg-blue-100 text-blue-800 rounded">
                                            {{ $ticket['title'] }}
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        @else
                        <div class="p-6">
                            <div class="space-y-4">
                                @forelse($this->getFilteredTickets() as $ticket)
                                <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow">
                                    <div class="flex justify-between items-start">
                                        <div>
                                            <h3 class="font-semibold text-gray-900">{{ $ticket['title'] }}</h3>
                                            <p class="text-sm text-gray-600 mt-1">
                                                Categoria: {{ $categories[$ticket['category']]['name'] }}
                                            </p>
                                            <p class="text-sm text-gray-500 mt-1">
                                                Coordinate: {{ $ticket['lat'] }}, {{ $ticket['lng'] }}
                                            </p>
                                        </div>
                                        <span class="px-2 py-1 text-xs rounded-full 
                                            @if($ticket['status'] === 'open') bg-red-100 text-red-800
                                            @elseif($ticket['status'] === 'in_progress') bg-yellow-100 text-yellow-800
                                            @else bg-green-100 text-green-800
                                            @endif">
                                            @if($ticket['status'] === 'open') Aperta
                                            @elseif($ticket['status'] === 'in_progress') In corso
                                            @else Risolta
                                            @endif
                                        </span>
                                    </div>
                                </div>
                                @empty
                                <div class="text-center py-8">
                                    <p class="text-gray-500">Nessuna segnalazione trovata</p>
                                </div>
                                @endforelse
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </main>
    </div>
</x-pub_theme::layouts.app>
@endvolt