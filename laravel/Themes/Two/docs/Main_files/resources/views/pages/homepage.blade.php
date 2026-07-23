{{-- Homepage Template - Bootstrap Italia Design System --}}
<x-layouts.main 
    title="Homepage"
    metaDescription="Portale istituzionale del Comune - Servizi digitali per i cittadini"
    :hideBreadcrumb="true">
    
    {{-- Hero Section --}}
    <section class="bg-primary-600 text-white py-16">
        <div class="container-italia">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                <div>
                    <h1 class="text-4xl lg:text-5xl font-bold mb-6 leading-tight">
                        Benvenuto nel portale del {{ config('app.name') }}
                    </h1>
                    <p class="text-xl text-primary-100 mb-8 leading-relaxed">
                        Accedi ai servizi digitali, consulta le informazioni e resta aggiornato su tutte le iniziative del territorio.
                    </p>
                    <div class="flex flex-col sm:flex-row gap-4">
                        <a href="/servizi" class="btn btn-outline-primary bg-white text-primary-600 hover:bg-primary-50">
                            Esplora i servizi
                        </a>
                        <a href="/contatti" class="btn btn-secondary bg-primary-700 hover:bg-primary-800 border-primary-700">
                            Contatta l'ufficio
                        </a>
                    </div>
                </div>
                <div class="hidden lg:block">
                    <img src="/images/hero-illustration.svg" 
                         alt="Illustrazione servizi digitali" 
                         class="w-full h-auto">
                </div>
            </div>
        </div>
    </section>

    {{-- Servizi in Evidenza --}}
    <section class="py-16 bg-white">
        <div class="container-italia">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-italia-gray-900 mb-4">
                    Servizi in evidenza
                </h2>
                <p class="text-lg text-italia-gray-600 max-w-2xl mx-auto">
                    I servizi più richiesti dai cittadini, sempre disponibili online
                </p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                {{-- Servizio 1 --}}
                <x-blocks.cards.italia
                    title="Certificati Anagrafici"
                    subtitle="Richiedi online"
                    hover
                    clickable
                    href="/servizi/certificati">
                    <p class="text-italia-gray-600 mb-4">
                        Richiedi e scarica certificati di nascita, residenza e stato di famiglia direttamente online.
                    </p>
                    <div class="flex items-center text-primary-600 font-medium">
                        <span>Vai al servizio</span>
                        <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </div>
                </x-blocks.cards.italia>

                {{-- Servizio 2 --}}
                <x-blocks.cards.italia 
                    title="Pagamenti Tributi"
                    subtitle="PagoPA"
                    hover
                    clickable
                    href="/servizi/pagamenti">
                    <p class="text-italia-gray-600 mb-4">
                        Paga tasse, multe e tributi comunali in modo sicuro con il sistema PagoPA.
                    </p>
                    <div class="flex items-center text-primary-600 font-medium">
                        <span>Vai al servizio</span>
                        <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </div>
                </x-blocks.cards.italia>

                {{-- Servizio 3 --}}
                <x-blocks.cards.italia 
                    title="Pratiche Edilizie"
                    subtitle="SUAP Online"
                    hover
                    clickable
                    href="/servizi/edilizia">
                    <p class="text-italia-gray-600 mb-4">
                        Presenta pratiche edilizie, richieste di permessi e consulta lo stato delle procedure.
                    </p>
                    <div class="flex items-center text-primary-600 font-medium">
                        <span>Vai al servizio</span>
                        <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </div>
                </x-blocks.cards.italia>
            </div>
            
            <div class="text-center mt-10">
                <a class="btn btn-outline-primary" href="/servizi">
                    Tutti i servizi
                </a>
            </div>
        </div>
    </section>

    {{-- Notizie --}}
    <section class="py-16 bg-italia-gray-50">
        <div class="container-italia">
            <div class="flex items-center justify-between mb-10">
                <div>
                    <h2 class="text-3xl font-bold text-italia-gray-900 mb-2">
                        Ultime notizie
                    </h2>
                    <p class="text-italia-gray-600">
                        Resta aggiornato su eventi e iniziative del territorio
                    </p>
                </div>
                <a class="btn btn-outline-primary" href="/notizie">
                    Tutte le notizie
                </a>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                {{-- Notizia 1 --}}
                <x-blocks.cards.italia
                    title="Nuovi orari uffici comunali"
                    image="/images/news-1.jpg"
                    imageAlt="Uffici comunali"
                    hover
                    clickable
                    href="/notizie/nuovi-orari-uffici">
                    <p class="text-sm text-italia-gray-500 mb-2">15 Gennaio 2024</p>
                    <p class="text-italia-gray-600">
                        A partire da lunedì 22 gennaio entreranno in vigore i nuovi orari di apertura al pubblico degli uffici comunali.
                    </p>
                </x-blocks.cards.italia>

                {{-- Notizia 2 --}}
                <x-blocks.cards.italia 
                    title="Raccolta differenziata: nuove modalità"
                    image="/images/news-2.jpg"
                    imageAlt="Raccolta differenziata"
                    hover
                    clickable
                    href="/notizie/raccolta-differenziata">
                    <p class="text-sm text-italia-gray-500 mb-2">12 Gennaio 2024</p>
                    <p class="text-italia-gray-600">
                        Implementate nuove modalità di raccolta differenziata per migliorare la qualità del servizio e l'ambiente.
                    </p>
                </x-blocks.cards.italia>

                {{-- Notizia 3 --}}
                <x-blocks.cards.italia 
                    title="Bando per contributi famiglie"
                    image="/images/news-3.jpg"
                    imageAlt="Contributi famiglie"
                    hover
                    clickable
                    href="/notizie/bando-contributi">
                    <p class="text-sm text-italia-gray-500 mb-2">10 Gennaio 2024</p>
                    <p class="text-italia-gray-600">
                        Pubblicato il bando per l'erogazione di contributi economici a sostegno delle famiglie in difficoltà.
                    </p>
                </x-blocks.cards.italia>
            </div>
        </div>
    </section>

    {{-- CTA Section --}}
    <section class="py-16 bg-primary-600 text-white">
        <div class="container-italia">
            <div class="text-center max-w-3xl mx-auto">
                <h2 class="text-3xl font-bold mb-6">
                    Hai bisogno di aiuto?
                </h2>
                <p class="text-xl text-primary-100 mb-8">
                    I nostri uffici sono a tua disposizione per fornirti supporto e assistenza
                </p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="/contatti" class="btn btn-outline-primary bg-white text-primary-600 hover:bg-primary-50">
                        Contatta gli uffici
                    </a>
                    <a href="tel:+390612345678" class="btn btn-secondary bg-primary-700 hover:bg-primary-800 border-primary-700">
                        Chiama: 06 1234567
                    </a>
                </div>
            </div>
        </div>
    </section>

</x-layouts.main>
