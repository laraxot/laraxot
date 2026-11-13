<?php

use function Laravel\Folio\name;

name('segnalazioni.create');
?>

@volt
new class extends \Livewire\Volt\Component {
    public $title = '';
    public $description = '';
    public $category = '';
    public $address = '';
    public $latitude = '';
    public $longitude = '';
    public $priority = 'medium';
    public $attachments = [];

    public $categories = [
        'acqua' => 'Acqua, allagamenti, problemi fognari',
        'ambiente' => 'Ambiente, inquinamento, protezione ambientale',
        'arredo' => 'Arredo urbano',
        'disinfestazione' => 'Disinfestazione, derattizzazione, animali randagi',
        'igiene' => 'Igiene urbana, rifiuti, pulizia e decoro',
        'manutenzione' => 'Manutenzione immobili, edifici pubblici, scuole, barriere architettoniche, cimiteri',
        'ordine' => 'Ordine pubblico, disturbo della quiete',
        'parchi' => 'Parchi e verde pubblico',
        'servizi' => 'Servizi del comune',
        'sicurezza' => 'Sicurezza, degrado urbano e sociale',
        'strade' => 'Strade, marciapiedi, segnaletica e viabilità',
    ];

    public $priorities = [
        'low' => 'Bassa',
        'medium' => 'Media',
        'high' => 'Alta',
        'urgent' => 'Urgente',
    ];

    protected $rules = [
        'title' => 'required|string|max:255',
        'description' => 'required|string|min:10',
        'category' => 'required|string',
        'address' => 'required|string',
        'priority' => 'required|string',
    ];

    public function save()
    {
        $this->validate();

        // Qui implementeremo la logica per salvare la segnalazione
        // Per ora simuliamo il salvataggio
        session()->flash('message', __('pub_theme::legacy_prototypes.form.saved.message'));
        
        return redirect()->to('/segnalazioni');
    }

    public function getLocation()
    {
        // Simuliamo il geocoding
        $this->address = 'Via Roma, 1, Firenze, Italia';
        $this->latitude = '43.7696';
        $this->longitude = '11.2558';
    }
} ?>

<x-pub_theme::layouts.app>
    <div class="min-h-screen bg-gray-50">
        <!-- Header Comunale -->
        <header class="bg-gradient-to-r from-blue-800 to-blue-900 text-white">
            <!-- Top Bar -->
            <div class="bg-blue-900 py-2">
                <div class="container mx-auto px-4 flex justify-between items-center text-sm">
                    <div class="flex items-center space-x-4">
                        <span>{{ __('pub_theme::legacy_prototypes.chrome.region.label') }}</span>
                    </div>
                    <div class="flex items-center space-x-4">
                        <button class="flex items-center space-x-1 hover:text-blue-200">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"></path>
                            </svg>
                            <span>{{ __('pub_theme::legacy_prototypes.chrome.dark_mode.label') }}</span>
                        </button>
                        <button class="flex items-center space-x-1 hover:text-blue-200">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM9.555 7.168A1 1 0 008 8v4a1 1 0 001.555.832l3-2a1 1 0 000-1.664l-3-2z" clip-rule="evenodd"></path>
                            </svg>
                            <span>{{ __('pub_theme::legacy_prototypes.chrome.language_code.label') }}</span>
                        </button>
                        <a href="#" class="flex items-center space-x-1 hover:text-blue-200">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path>
                            </svg>
                            <span>{{ __('pub_theme::legacy_prototypes.chrome.personal_area.label') }}</span>
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
                                <h1 class="text-2xl font-bold">{{ __('pub_theme::legacy_prototypes.chrome.municipality.title') }}</h1>
                                <p class="text-sm text-blue-200">{{ __('pub_theme::legacy_prototypes.chrome.tagline.label') }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="flex items-center space-x-6">
                        <div class="flex items-center space-x-2">
                            <span class="text-sm">{{ __('pub_theme::legacy_prototypes.chrome.follow_us.label') }}</span>
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
                            <input type="text" placeholder="{{ __('pub_theme::legacy_prototypes.chrome.search.placeholder') }}" class="px-3 py-2 rounded text-gray-800 text-sm w-64">
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
                        <a href="#" class="text-white hover:text-blue-200 font-medium">{{ __('pub_theme::legacy_prototypes.chrome.nav_administration.label') }}</a>
                        <a href="#" class="text-white hover:text-blue-200 font-medium">{{ __('pub_theme::legacy_prototypes.chrome.nav_news.label') }}</a>
                        <a href="#" class="text-white hover:text-blue-200 font-medium">{{ __('pub_theme::legacy_prototypes.chrome.nav_services.label') }}</a>
                        <a href="#" class="text-white hover:text-blue-200 font-medium">{{ __('pub_theme::legacy_prototypes.chrome.nav_community.label') }}</a>
                    </div>
                    <div class="flex space-x-6 py-2 border-t border-blue-600">
                        <a href="#" class="text-white hover:text-blue-200 text-sm">{{ __('pub_theme::legacy_prototypes.chrome.nav_enrollments.label') }}</a>
                        <a href="#" class="text-white hover:text-blue-200 text-sm">{{ __('pub_theme::legacy_prototypes.chrome.nav_summer.label') }}</a>
                        <a href="#" class="text-white hover:text-blue-200 text-sm">{{ __('pub_theme::legacy_prototypes.chrome.nav_local_police.label') }}</a>
                        <a href="#" class="text-white hover:text-blue-200 text-sm">{{ __('pub_theme::legacy_prototypes.chrome.nav_all_topics.label') }} ></a>
                    </div>
                </div>
            </nav>
        </header>

        <!-- Main Content -->
        <main class="container mx-auto px-4 py-8">
            <!-- Breadcrumbs -->
            <nav class="mb-6">
                <ol class="flex items-center space-x-2 text-sm text-gray-600">
                    <li><a href="/" class="hover:text-blue-600">{{ __('pub_theme::tickets_list.breadcrumb.home.label') }}</a></li>
                    <li class="flex items-center">
                        <svg class="w-4 h-4 mx-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                        </svg>
                        <a href="/segnalazioni" class="hover:text-blue-600">{{ __('pub_theme::legacy_prototypes.form.breadcrumb_list.label') }}</a>
                    </li>
                    <li class="flex items-center">
                        <svg class="w-4 h-4 mx-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                        </svg>
                        <span class="text-gray-900 font-medium">{{ __('pub_theme::legacy_prototypes.form.breadcrumb_new.label') }}</span>
                    </li>
                </ol>
            </nav>

            <!-- Page Title -->
            <div class="mb-8">
                <h1 class="text-4xl font-bold text-gray-900 mb-4">{{ __('pub_theme::legacy_prototypes.form.page.title') }}</h1>
                <p class="text-lg text-gray-600">{{ __('pub_theme::legacy_prototypes.form.page.help') }}</p>
            </div>

            <!-- Form -->
            <div class="max-w-4xl mx-auto">
                <form wire:submit="save" class="bg-white rounded-lg shadow-sm border p-8">
                    @if (session()->has('message'))
                        <div class="mb-6 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
                            {{ session('message') }}
                        </div>
                    @endif

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Titolo -->
                        <div class="md:col-span-2">
                            <label for="title" class="block text-sm font-medium text-gray-700 mb-2">
                                {{ __('pub_theme::legacy_prototypes.form.title.label') }}
                            </label>
                            <input type="text" 
                                   id="title"
                                   wire:model="title" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                   placeholder="{{ __('pub_theme::legacy_prototypes.form.title.placeholder') }}">
                            @error('title') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <!-- Categoria -->
                        <div>
                            <label for="category" class="block text-sm font-medium text-gray-700 mb-2">
                                {{ __('pub_theme::legacy_prototypes.form.category.label') }}
                            </label>
                            <select id="category" 
                                    wire:model="category" 
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <option value="">{{ __('pub_theme::legacy_prototypes.form.category.placeholder') }}</option>
                                @foreach($categories as $key => $name)
                                    <option value="{{ $key }}">{{ $name }}</option>
                                @endforeach
                            </select>
                            @error('category') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <!-- Priorità -->
                        <div>
                            <label for="priority" class="block text-sm font-medium text-gray-700 mb-2">
                                {{ __('pub_theme::legacy_prototypes.form.priority.label') }}
                            </label>
                            <select id="priority" 
                                    wire:model="priority" 
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                @foreach($priorities as $key => $name)
                                    <option value="{{ $key }}">{{ $name }}</option>
                                @endforeach
                            </select>
                            @error('priority') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <!-- Indirizzo -->
                        <div class="md:col-span-2">
                            <label for="address" class="block text-sm font-medium text-gray-700 mb-2">
                                {{ __('pub_theme::legacy_prototypes.form.address.label') }}
                            </label>
                            <div class="flex space-x-2">
                                <input type="text" 
                                       id="address"
                                       wire:model="address" 
                                       class="flex-1 px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                       placeholder="{{ __('pub_theme::legacy_prototypes.form.address.placeholder') }}">
                                <button type="button" 
                                        wire:click="getLocation"
                                        class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    {{ __('pub_theme::legacy_prototypes.form.location.label') }}
                                </button>
                            </div>
                            @error('address') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <!-- Descrizione -->
                        <div class="md:col-span-2">
                            <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                                {{ __('pub_theme::legacy_prototypes.form.description.label') }}
                            </label>
                            <textarea id="description" 
                                      wire:model="description" 
                                      rows="4" 
                                      class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                      placeholder="{{ __('pub_theme::legacy_prototypes.form.description.placeholder') }}"></textarea>
                            @error('description') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <!-- Allegati -->
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                {{ __('pub_theme::legacy_prototypes.form.attachments.label') }}
                            </label>
                            <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center">
                                <svg class="w-12 h-12 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                                </svg>
                                <p class="text-gray-500">{{ __('pub_theme::legacy_prototypes.form.attachments.help') }}</p>
                                <p class="text-sm text-gray-400 mt-1">{{ __('pub_theme::legacy_prototypes.form.attachments_limits.help') }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Submit Buttons -->
                    <div class="flex justify-end space-x-4 mt-8 pt-6 border-t border-gray-200">
                        <a href="/segnalazioni" 
                           class="px-6 py-2 border border-gray-300 text-gray-700 rounded-md hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            {{ __('pub_theme::legacy_prototypes.form.cancel.label') }}
                        </a>
                        <button type="submit" 
                                class="px-6 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            {{ __('pub_theme::legacy_prototypes.form.submit.label') }}
                        </button>
                    </div>
                </form>
            </div>
        </main>
    </div>
</x-pub_theme::layouts.app>
@endvolt