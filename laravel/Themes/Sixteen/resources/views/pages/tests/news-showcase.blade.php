{{-- Template Listing Notizie/Comunicati - AGID Compliant --}}
<x-layouts.main 
    title="Notizie e Comunicati"
    metaDescription="Notizie, comunicati stampa e avvisi del Comune - Tutte le informazioni ufficiali"
    breadcrumbTitle="Notizie"
>
    
    {{-- Hero Section --}}
    <section class="bg-primary-600 text-white py-12">
        <div class="container-italia">
            <div class="max-w-4xl mx-auto text-center">
                <h1 class="text-4xl font-bold mb-6">Notizie e Comunicati</h1>
                <p class="text-xl text-primary-100 mb-8">
                    Tutte le notizie ufficiali, i comunicati stampa e gli avvisi del Comune
                </p>
                
                {{-- Quick Filters --}}
                <div class="flex flex-wrap justify-center gap-4 mt-8">
                    <a href="#tutte" class="inline-flex items-center px-6 py-3 bg-white text-primary-600 font-semibold rounded-lg hover:bg-primary-50 transition-colors">
                        <x-heroicon-o-newspaper class="w-5 h-5 mr-2" />
                        Tutte le notizie
                    </a>
                    <a href="#comunicati" class="inline-flex items-center px-6 py-3 bg-white text-primary-600 font-semibold rounded-lg hover:bg-primary-50 transition-colors">
                        <x-heroicon-o-megaphone class="w-5 h-5 mr-2" />
                        Comunicati Stampa
                    </a>
                    <a href="#avvisi" class="inline-flex items-center px-6 py-3 bg-white text-primary-600 font-semibold rounded-lg hover:bg-primary-50 transition-colors">
                        <x-heroicon-o-bell-alert class="w-5 h-5 mr-2" />
                        Avvisi Pubblici
                    </a>
                </div>
            </div>
        </div>
    </section>

    {{-- Main Content --}}
    <section class="py-16 bg-white" aria-labelledby="notizie-heading">
        <div class="container-italia">
            
            {{-- Page Header with Filters --}}
            <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center mb-12">
                <div>
                    <h2 id="notizie-heading" class="text-3xl font-bold text-gray-900 mb-2">
                        Ultime notizie
                    </h2>
                    <p class="text-lg text-gray-600">
                        Resta aggiornato sulle novità del Comune
                    </p>
                </div>
                
                {{-- Search and Filters --}}
                <div class="flex flex-col sm:flex-row gap-4 mt-4 lg:mt-0">
                    {{-- Search Box --}}
                    <div class="relative">
                        <input 
                            type="text" 
                            placeholder="Cerca notizie..." 
                            class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
                        >
                        <x-heroicon-o-magnifying-glass class="w-5 h-5 text-gray-400 absolute left-3 top-1/2 transform -translate-y-1/2" />
                    </div>
                    
                    {{-- Filter Dropdown --}}
                    <select class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                        <option value="">Tutte le categorie</option>
                        <option value="comunicati">Comunicati Stampa</option>
                        <option value="avvisi">Avvisi Pubblici</option>
                        <option value="eventi">Eventi</option>
                        <option value="lavori">Lavori Pubblici</option>
                        <option value="bandi">Bandi e Gare</option>
                    </select>
                </div>
            </div>

            {{-- News Grid --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 mb-12">
                
                {{-- News Item 1 --}}
                <article class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden hover:shadow-md transition-shadow" role="article">
                    <div class="aspect-w-16 aspect-h-9 bg-gray-200">
                        <img 
                            src="/images/news-1.jpg" 
                            alt="Nuovi orari uffici comunali" 
                            class="w-full h-48 object-cover"
                        >
                    </div>
                    
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-3">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                Comunicato
                            </span>
                            <time datetime="2024-01-15" class="text-sm text-gray-500">15 Gen 2024</time>
                        </div>
                        
                        <h3 class="text-xl font-semibold text-gray-900 mb-3 hover:text-primary-600 transition-colors">
                            <a href="{{ route('pages.view', ['slug' => 'nuovi-orari-uffici']) }}">
                                Nuovi orari di apertura al pubblico degli uffici comunali
                            </a>
                        </h3>
                        
                        <p class="text-gray-600 mb-4 line-clamp-3">
                            A partire da lunedì 22 gennaio entreranno in vigore i nuovi orari di apertura al pubblico degli uffici comunali. Maggiori informazioni sugli orari e i servizi disponibili.
                        </p>
                        
                        <div class="flex items-center justify-between">
                            <a href="{{ route('pages.view', ['slug' => 'nuovi-orari-uffici']) }}" class="inline-flex items-center text-primary-600 font-medium hover:text-primary-800">
                                Leggi tutto
                                <x-heroicon-o-arrow-right class="w-4 h-4 ml-1" />
                            </a>
                            
                            <div class="flex items-center space-x-2">
                                <button class="p-1 text-gray-400 hover:text-primary-600 transition-colors" aria-label="Condividi">
                                    <x-heroicon-o-share class="w-4 h-4" />
                                </button>
                                <button class="p-1 text-gray-400 hover:text-primary-600 transition-colors" aria-label="Salva">
                                    <x-heroicon-o-bookmark class="w-4 h-4" />
                                </button>
                            </div>
                        </div>
                    </div>
                </article>

                {{-- News Item 2 --}}
                <article class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden hover:shadow-md transition-shadow" role="article">
                    <div class="aspect-w-16 aspect-h-9 bg-gray-200">
                        <img 
                            src="/images/news-2.jpg" 
                            alt="Raccolta differenziata nuove modalità" 
                            class="w-full h-48 object-cover"
                        >
                    </div>
                    
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-3">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                Avviso
                            </span>
                            <time datetime="2024-01-12" class="text-sm text-gray-500">12 Gen 2024</time>
                        </div>
                        
                        <h3 class="text-xl font-semibold text-gray-900 mb-3 hover:text-primary-600 transition-colors">
                            <a href="{{ route('pages.view', ['slug' => 'raccolta-differenziata']) }}">
                                Raccolta differenziata: implementate nuove modalità
                            </a>
                        </h3>
                        
                        <p class="text-gray-600 mb-4 line-clamp-3">
                            Implementate nuove modalità di raccolta differenziata per migliorare la qualità del servizio e contribuire alla tutela dell'ambiente. Scopri le novità.
                        </p>
                        
                        <div class="flex items-center justify-between">
                            <a href="{{ route('pages.view', ['slug' => 'raccolta-differenziata']) }}" class="inline-flex items-center text-primary-600 font-medium hover:text-primary-800">
                                Leggi tutto
                                <x-heroicon-o-arrow-right class="w-4 h-4 ml-1" />
                            </a>
                            
                            <div class="flex items-center space-x-2">
                                <button class="p-1 text-gray-400 hover:text-primary-600 transition-colors" aria-label="Condividi">
                                    <x-heroicon-o-share class="w-4 h-4" />
                                </button>
                                <button class="p-1 text-gray-400 hover:text-primary-600 transition-colors" aria-label="Salva">
                                    <x-heroicon-o-bookmark class="w-4 h-4" />
                                </button>
                            </div>
                        </div>
                    </div>
                </article>

                {{-- News Item 3 --}}
                <article class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden hover:shadow-md transition-shadow" role="article">
                    <div class="aspect-w-16 aspect-h-9 bg-gray-200">
                        <img 
                            src="/images/news-3.jpg" 
                            alt="Bando contributi famiglie" 
                            class="w-full h-48 object-cover"
                        >
                    </div>
                    
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-3">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                Bando
                            </span>
                            <time datetime="2024-01-10" class="text-sm text-gray-500">10 Gen 2024</time>
                        </div>
                        
                        <h3 class="text-xl font-semibold text-gray-900 mb-3 hover:text-primary-600 transition-colors">
                            <a href="{{ route('pages.view', ['slug' => 'bando-contributi-famiglie']) }}">
                                Pubblicato il bando per contributi economici a sostegno delle famiglie
                            </a>
                        </h3>
                        
                        <p class="text-gray-600 mb-4 line-clamp-3">
                            Pubblicato il bando per l'erogazione di contributi economici a sostegno delle famiglie in difficoltà. Scadenza presentazione domande: 31 gennaio 2024.
                        </p>
                        
                        <div class="flex items-center justify-between">
                            <a href="{{ route('pages.view', ['slug' => 'bando-contributi-famiglie']) }}" class="inline-flex items-center text-primary-600 font-medium hover:text-primary-800">
                                Leggi tutto
                                <x-heroicon-o-arrow-right class="w-4 h-4 ml-1" />
                            </a>
                            
                            <div class="flex items-center space-x-2">
                                <button class="p-1 text-gray-400 hover:text-primary-600 transition-colors" aria-label="Condividi">
                                    <x-heroicon-o-share class="w-4 h-4" />
                                </button>
                                <button class="p-1 text-gray-400 hover:text-primary-600 transition-colors" aria-label="Salva">
                                    <x-heroicon-o-bookmark class="w-4 h-4" />
                                </button>
                            </div>
                        </div>
                    </div>
                </article>

                {{-- News Item 4 --}}
                <article class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden hover:shadow-md transition-shadow" role="article">
                    <div class="aspect-w-16 aspect-h-9 bg-gray-200">
                        <img 
                            src="/images/news-4.jpg" 
                            alt="Evento culturale primavera" 
                            class="w-full h-48 object-cover"
                        >
                    </div>
                    
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-3">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                Evento
                            </span>
                            <time datetime="2024-01-08" class="text-sm text-gray-500">8 Gen 2024</time>
                        </div>
                        
                        <h3 class="text-xl font-semibold text-gray-900 mb-3 hover:text-primary-600 transition-colors">
                            <a href="{{ route('pages.view', ['slug' => 'evento-culturale-primavera']) }}">
                                Evento culturale di primavera: programma e partecipazione
                            </a>
                        </h3>
                        
                        <p class="text-gray-600 mb-4 line-clamp-3">
                            Presentato il programma dell'evento culturale di primavera. Mostre, concerti e incontri culturali dal 15 marzo al 15 aprile 2024.
                        </p>
                        
                        <div class="flex items-center justify-between">
                            <a href="{{ route('pages.view', ['slug' => 'evento-culturale-primavera']) }}" class="inline-flex items-center text-primary-600 font-medium hover:text-primary-800">
                                Leggi tutto
                                <x-heroicon-o-arrow-right class="w-4 h-4 ml-1" />
                            </a>
                            
                            <div class="flex items-center space-x-2">
                                <button class="p-1 text-gray-400 hover:text-primary-600 transition-colors" aria-label="Condividi">
                                    <x-heroicon-o-share class="w-4 h-4" />
                                </button>
                                <button class="p-1 text-gray-400 hover:text-primary-600 transition-colors" aria-label="Salva">
                                    <x-heroicon-o-bookmark class="w-4 h-4" />
                                </button>
                            </div>
                        </div>
                    </div>
                </article>

                {{-- News Item 5 --}}
                <article class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden hover:shadow-md transition-shadow" role="article">
                    <div class="aspect-w-16 aspect-h-9 bg-gray-200">
                        <img 
                            src="/images/news-5.jpg" 
                            alt="Lavori stradali via Roma" 
                            class="w-full h-48 object-cover"
                        >
                    </div>
                    
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-3">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-orange-100 text-orange-800">
                                Lavori
                            </span>
                            <time datetime="2024-01-05" class="text-sm text-gray-500">5 Gen 2024</time>
                        </div>
                        
                        <h3 class="text-xl font-semibold text-gray-900 mb-3 hover:text-primary-600 transition-colors">
                            <a href="{{ route('pages.view', ['slug' => 'lavori-stradali-via-roma']) }}">
                                Lavori stradali in via Roma: modifiche alla viabilità
                            </a>
                        </h3>
                        
                        <p class="text-gray-600 mb-4 line-clamp-3">
                            A partire da lunedì 8 gennaio inizieranno i lavori di manutenzione straordinaria in via Roma. Modifiche alla viabilità e percorsi alternativi.
                        </p>
                        
                        <div class="flex items-center justify-between">
                            <a href="{{ route('pages.view', ['slug' => 'lavori-stradali-via-roma']) }}" class="inline-flex items-center text-primary-600 font-medium hover:text-primary-800">
                                Leggi tutto
                                <x-heroicon-o-arrow-right class="w-4 h-4 ml-1" />
                            </a>
                            
                            <div class="flex items-center space-x-2">
                                <button class="p-1 text-gray-400 hover:text-primary-600 transition-colors" aria-label="Condividi">
                                    <x-heroicon-o-share class="w-4 h-4" />
                                </button>
                                <button class="p-1 text-gray-400 hover:text-primary-600 transition-colors" aria-label="Salva">
                                    <x-heroicon-o-bookmark class="w-4 h-4" />
                                </button>
                            </div>
                        </div>
                    </div>
                </article>

                {{-- News Item 6 --}}
                <article class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden hover:shadow-md transition-shadow" role="article">
                    <div class="aspect-w-16 aspect-h-9 bg-gray-200">
                        <img 
                            src="/images/news-6.jpg" 
                            alt="Servizi digitali nuovi" 
                            class="w-full h-48 object-cover"
                        >
                    </div>
                    
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-3">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                Servizi
                            </span>
                            <time datetime="2024-01-03" class="text-sm text-gray-500">3 Gen 2024</time>
                        </div>
                        
                        <h3 class="text-xl font-semibold text-gray-900 mb-3 hover:text-primary-600 transition-colors">
                            <a href="{{ route('pages.view', ['slug' => 'nuovi-servizi-digitali']) }}">
                                Attivati nuovi servizi digitali per i cittadini
                            </a>
                        </h3>
                        
                        <p class="text-gray-600 mb-4 line-clamp-3">
                            Dal 10 gennaio attivi nuovi servizi digitali: prenotazione appuntamenti online, richiesta certificati e pagamento tributi con PagoPA.
                        </p>
                        
                        <div class="flex items-center justify-between">
                            <a href="{{ route('pages.view', ['slug' => 'nuovi-servizi-digitali']) }}" class="inline-flex items-center text-primary-600 font-medium hover:text-primary-800">
                                Leggi tutto
                                <x-heroicon-o-arrow-right class="w-4 h-4 ml-1" />
                            </a>
                            
                            <div class="flex items-center space-x-2">
                                <button class="p-1 text-gray-400 hover:text-primary-600 transition-colors" aria-label="Condividi">
                                    <x-heroicon-o-share class="w-4 h-4" />
                                </button>
                                <button class="p-1 text-gray-400 hover:text-primary-600 transition-colors" aria-label="Salva">
                                    <x-heroicon-o-bookmark class="w-4 h-4" />
                                </button>
                            </div>
                        </div>
                    </div>
                </article>
            </div>

            {{-- Pagination --}}
            <nav aria-label="Paginazione notizie" class="flex justify-center">
                <ul class="inline-flex items-center space-x-1">
                    <li>
                        <a href="#" class="px-3 py-2 text-gray-500 hover:text-gray-700" aria-label="Pagina precedente">
                            <x-heroicon-o-arrow-left class="w-4 h-4" />
                        </a>
                    </li>
                    <li>
                        <a href="#" class="px-3 py-2 text-white bg-primary-600 rounded-lg" aria-current="page">1</a>
                    </li>
                    <li>
                        <a href="#" class="px-3 py-2 text-gray-700 hover:text-primary-600">2</a>
                    </li>
                    <li>
                        <a href="#" class="px-3 py-2 text-gray-700 hover:text-primary-600">3</a>
                    </li>
                    <li>
                        <span class="px-3 py-2 text-gray-500">...</span>
                    </li>
                    <li>
                        <a href="#" class="px-3 py-2 text-gray-700 hover:text-primary-600">10</a>
                    </li>
                    <li>
                        <a href="#" class="px-3 py-2 text-gray-500 hover:text-gray-700" aria-label="Pagina successiva">
                            <x-heroicon-o-arrow-right class="w-4 h-4" />
                        </a>
                    </li>
                </ul>
            </nav>

            {{-- RSS Subscription --}}
            <div class="mt-12 bg-gray-50 rounded-lg p-8 text-center">
                <div class="flex items-center justify-center mb-4">
                    <x-heroicon-o-rss class="w-8 h-8 text-orange-500 mr-2" />
                    <h3 class="text-xl font-semibold text-gray-900">Rimani aggiornato</h3>
                </div>
                <p class="text-gray-600 mb-6">
                    Iscriviti al feed RSS per ricevere tutte le notizie e gli aggiornamenti del Comune
                </p>
                <a href="/rss/news" class="inline-flex items-center px-6 py-3 bg-orange-500 text-white font-semibold rounded-lg hover:bg-orange-600 transition-colors">
                    <x-heroicon-o-arrow-down-tray class="w-5 h-5 mr-2" />
                    Sottoscrivi RSS Feed
                </a>
            </div>
        </div>
    </section>

    {{-- Newsletter Section --}}
    <section class="py-16 bg-primary-50">
        <div class="container-italia">
            <div class="max-w-2xl mx-auto text-center">
                <h2 class="text-3xl font-bold text-gray-900 mb-4">Newsletter istituzionale</h2>
                <p class="text-lg text-gray-600 mb-8">
                    Ricevi tutte le notizie e gli aggiornamenti direttamente nella tua email
                </p>
                
                <form class="flex flex-col sm:flex-row gap-4">
                    <input 
                        type="email" 
                        placeholder="La tua email" 
                        required
                        class="flex-1 px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
                    >
                    <button 
                        type="submit" 
                        class="px-6 py-3 bg-primary-600 text-white font-semibold rounded-lg hover:bg-primary-700 transition-colors"
                    >
                        Iscriviti
                    </button>
                </form>
                
                <p class="text-sm text-gray-500 mt-4">
                    Potrai disiscriverti in qualsiasi momento. Rispettiamo la tua privacy.
                </p>
            </div>
        </div>
    </section>

</x-layouts.main>

<style>
.line-clamp-3 {
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.aspect-w-16 {
    position: relative;
}

.aspect-w-16::before {
    content: "";
    display: block;
    padding-bottom: 56.25%; /* 16:9 aspect ratio */
}

.aspect-w-16 > * {
    position: absolute;
    height: 100%;
    width: 100%;
    top: 0;
    right: 0;
    bottom: 0;
    left: 0;
}

/* Focus styles for accessibility */
article:focus-within {
    outline: 2px solid #0066CC;
    outline-offset: 2px;
}

/* High contrast mode */
@media (prefers-contrast: high) {
    article {
        border: 2px solid currentColor;
    }
    
    .bg-gray-50 {
        background: #f0f4f8 !important;
    }
}

/* Reduced motion */
@media (prefers-reduced-motion: reduce) {
    article {
        transition: none;
    }
}

/* Mobile optimization */
@media (max-width: 768px) {
    .container-italia {
        padding: 0 1rem;
    }
    
    .flex.flex-col.lg\:flex-row {
        flex-direction: column;
        gap: 1rem;
    }
    
    .grid.grid-cols-1.md\:grid-cols-2.lg\:grid-cols-3 {
        grid-template-columns: 1fr;
    }
}
</style>