@php
declare(strict_types=1);

@props([
    'title' => 'Trova l\'Articolo Perfetto',
    'subtitle' => 'Cerca tra centinaia di guide, aggiornamenti normativi e approfondimenti',
    'placeholder' => 'Cerca per argomento, normativa o parola chiave...',
    'show_advanced' => true,
    'show_suggestions' => true
])
?>

{{-- Blog Search Section --}}
<section class="py-16 bg-gradient-to-b from-gray-50 to-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        {{-- Header --}}
        <div class="text-center mb-12">
            <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
                {{ $title }}
            </h2>
            <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                {{ $subtitle }}
            </p>
        </div>
        
        {{-- Search Form --}}
        <div class="max-w-4xl mx-auto">
            <form action="{{ route('blog.search') }}" method="GET" class="relative">
                <div class="flex items-center bg-white rounded-2xl shadow-2xl border border-gray-200 overflow-hidden focus-within:ring-4 focus-within:ring-blue-500/20 transition-all duration-300">
                    {{-- Search Icon --}}
                    <div class="pl-6 pr-4">
                        <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>
                    
                    {{-- Search Input --}}
                    <label for="blog-search-input" class="sr-only">Cerca articoli</label>
                    <input type="text" 
                           id="blog-search-input"
                           name="q" 
                           placeholder="{{ $placeholder }}" 
                           autocomplete="off"
                           aria-label="Cerca articoli per argomento, normativa o parola chiave"
                           class="flex-1 py-5 px-4 text-lg text-gray-700 bg-transparent border-none focus:outline-none focus:ring-0 placeholder-gray-400"
                           x-model="searchQuery"
                           @keydown.enter.prevent="search">
                    
                    {{-- Search Button --}}
                    <button type="submit" 
                            aria-label="Esegui ricerca articoli"
                            class="px-8 py-5 bg-gradient-to-r from-blue-600 to-blue-700 text-white font-semibold hover:from-blue-700 hover:to-blue-800 transition-all duration-300 transform hover:scale-105 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Cerca
                    </button>
                </div>
            </form>
            
            {{-- Advanced Filters Toggle --}}
            @if($show_advanced)
                <button @click="showAdvanced = !showAdvanced" 
                        aria-label="Mostra/Nascondi opzioni avanzate di ricerca"
                        aria-expanded="false"
                        :aria-expanded="showAdvanced.toString()"
                        class="mt-4 text-blue-600 hover:text-blue-800 font-medium flex items-center justify-center mx-auto transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 rounded-md px-2 py-1">
                    <span>Opzioni avanzate</span>
                    <svg class="w-5 h-5 ml-2 transform transition-transform duration-200" 
                         :class="{ 'rotate-180': showAdvanced }" 
                         fill="none" 
                         stroke="currentColor" 
                         viewBox="0 0 24 24"
                         aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>
                
                {{-- Advanced Filters Panel --}}
                <div x-show="showAdvanced" 
                     x-collapse
                     class="mt-6 bg-white rounded-xl shadow-lg border border-gray-200 p-6">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        {{-- Date Range --}}
                        <div>
                            <label for="blog-search-date" class="block text-sm font-semibold text-gray-700 mb-2">Data</label>
                            <select id="blog-search-date" 
                                    name="date"
                                    autocomplete="off"
                                    class="w-full px-4 py-3 bg-gray-50 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#1E5A96] focus:border-transparent">
                                <option value="">Tutte le date</option>
                                <option value="7days">Ultimi 7 giorni</option>
                                <option value="30days">Ultimi 30 giorni</option>
                                <option value="90days">Ultimi 3 mesi</option>
                                <option value="1year">Ultimo anno</option>
                            </select>
                        </div>
                        
                        {{-- Category --}}
                        <div>
                            <label for="blog-search-category" class="block text-sm font-semibold text-gray-700 mb-2">Categoria</label>
                            <select id="blog-search-category" 
                                    name="category"
                                    autocomplete="off"
                                    class="w-full px-4 py-3 bg-gray-50 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#1E5A96] focus:border-transparent">
                                <option value="">Tutte le categorie</option>
                                <option value="radioprotezione">Radioprotezione</option>
                                <option value="normativa">Normativa</option>
                                <option value="elettromedicali">Elettromedicali</option>
                                <option value="guide">Guide Pratiche</option>
                                <option value="veterinaria">Veterinaria</option>
                                <option value="novita">Novità</option>
                            </select>
                        </div>
                        
                        {{-- Sort By --}}
                        <div>
                            <label for="blog-search-sort" class="block text-sm font-semibold text-gray-700 mb-2">Ordina per</label>
                            <select id="blog-search-sort" 
                                    name="sort"
                                    autocomplete="off"
                                    class="w-full px-4 py-3 bg-gray-50 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#1E5A96] focus:border-transparent">
                                <option value="relevance">Rilevanza</option>
                                <option value="date-desc">Più recenti</option>
                                <option value="date-asc">Più vecchi</option>
                                <option value="popular">Più popolari</option>
                            </select>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</section>

<script>
    if (typeof Alpine !== 'undefined') {
        Alpine.data('search', () => ({
            searchQuery: '',
            showAdvanced: false,
            search() {
                // Form submission
            }
        }))
    }
</script>