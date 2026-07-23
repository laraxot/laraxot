{{-- Template Pagina Servizi - AGID Compliant --}}
<x-layouts.main 
    title="Servizi del Comune"
    metaDescription="Tutti i servizi digitali e tradizionali del Comune - Accesso rapido e semplice ai servizi per i cittadini"
    breadcrumbTitle="Servizi"
>
    
    {{-- Hero Section --}}
    <section class="bg-primary-600 text-white py-12">
        <div class="container-italia">
            <div class="max-w-4xl mx-auto text-center">
                <h1 class="text-4xl font-bold mb-6">Servizi del Comune</h1>
                <p class="text-xl text-primary-100 mb-8">
                    Accedi a tutti i servizi digitali e tradizionali del Comune in modo semplice e rapido
                </p>
                
                {{-- Quick Search --}}
                <div class="relative max-w-2xl mx-auto">
                    <input 
                        type="text" 
                        placeholder="Cerca servizi..." 
                        class="w-full px-6 py-4 pr-12 text-gray-900 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500"
                    >
                    <x-heroicon-o-magnifying-glass class="w-5 h-5 text-gray-400 absolute right-4 top-1/2 transform -translate-y-1/2" />
                </div>
            </div>
        </div>
    </section>

    {{-- Services Navigation --}}
    <section class="py-8 bg-gray-50 border-b border-gray-200">
        <div class="container-italia">
            <nav aria-label="Categorie servizi" class="flex flex-wrap justify-center gap-4">
                <a href="#tutti" class="px-6 py-3 bg-primary-600 text-white font-semibold rounded-lg">
                    Tutti i servizi
                </a>
                <a href="#anagrafe" class="px-6 py-3 bg-white text-gray-700 font-semibold rounded-lg border border-gray-300 hover:bg-gray-50 transition-colors">
                    Anagrafe e Stato Civile
                </a>
                <a href="#tributi" class="px-6 py-3 bg-white text-gray-700 font-semibold rounded-lg border border-gray-300 hover:bg-gray-50 transition-colors">
                    Tributi e Imposte
                </a>
                <a href="#urbanistica" class="px-6 py-3 bg-white text-gray-700 font-semibold rounded-lg border border-gray-300 hover:bg-gray-50 transition-colors">
                    Urbanistica e Edilizia
                </a>
                <a href="#sociale" class="px-6 py-3 bg-white text-gray-700 font-semibold rounded-lg border border-gray-300 hover:bg-gray-50 transition-colors">
                    Servizi Sociali
                </a>
                <a href="#ambiente" class="px-6 py-3 bg-white text-gray-700 font-semibold rounded-lg border border-gray-300 hover:bg-gray-50 transition-colors">
                    Ambiente e Verde
                </a>
            </nav>
        </div>
    </section>

    {{-- Featured Services --}}
    <section class="py-16 bg-white" aria-labelledby="featured-services-heading">
        <div class="container-italia">
            <div class="text-center mb-12">
                <h2 id="featured-services-heading" class="text-3xl font-bold text-gray-900 mb-4">Servizi in Evidenza</h2>
                <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                    I servizi più richiesti e utilizzati dai cittadini
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                
                {{-- SUAP --}}
                <x-pub_theme::blocks.services.service-card
                    title="SUAP - Sportello Unico"
                    description="Presenta pratiche edilizie, richieste di permessi e consulta lo stato delle procedure online"
                    icon="heroicon-o-building-office"
                    url="/servizi/suap"
                    category="urbanistica"
                    status="active"
                    requiresAuth="true"
                    badge="Nuovo"
                />

                {{-- Anagrafe --}}
                <x-pub_theme::blocks.services.service-card
                    title="Certificati Anagrafici"
                    description="Richiedi e scarica certificati di nascita, residenza e stato di famiglia direttamente online"
                    icon="heroicon-o-document-text"
                    url="/servizi/anagrafe/certificati"
                    category="anagrafe"
                    status="active"
                    requiresAuth="true"
                />

                {{-- Tributi --}}
                <x-ui.service-card
                    title="Pagamento Tributi"
                    description="Paga tasse, multe e tributi comunali in modo sicuro con il sistema PagoPA"
                    icon="heroicon-o-currency-euro"
                    url="/servizi/tributi/pagamento"
                    category="tributi"
                    status="active"
                />

                {{-- Segnalazioni --}}
                <x-ui.service-card
                    title="Segnalazioni"
                    description="Segnala guasti, disservizi o problematiche sul territorio comunale"
                    icon="heroicon-o-exclamation-triangle"
                    url="/servizi/segnalazioni"
                    category="ambiente"
                    status="active"
                />

                {{-- Prenotazioni --}}
                <x-ui.service-card
                    title="Prenotazione Appuntamenti"
                    description="Prenota online appuntamenti con gli uffici comunali senza code né attese"
                    icon="heroicon-o-calendar"
                    url="/servizi/prenotazioni"
                    category="anagrafe"
                    status="active"
                />

                {{-- Sociali --}}
                <x-ui.service-card
                    title="Servizi Sociali"
                    description="Accedi ai servizi sociali, richiedi contributi e supporto per le famiglie"
                    icon="heroicon-o-heart"
                    url="/servizi/sociali"
                    category="sociale"
                    status="active"
                    requiresAuth="true"
                />
            </div>
        </div>
    </section>

    {{-- All Services by Category --}}
    <section class="py-16 bg-gray-50" aria-labelledby="all-services-heading">
        <div class="container-italia">
            <div class="text-center mb-12">
                <h2 id="all-services-heading" class="text-3xl font-bold text-gray-900 mb-4">Tutti i Servizi per Categoria</h2>
                <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                    Scegli la categoria di servizi che ti interessa
                </p>
            </div>

            {{-- Anagrafe e Stato Civile --}}
            <div id="anagrafe" class="mb-16">
                <div class="flex items-center mb-8">
                    <x-heroicon-o-user-circle class="w-8 h-8 text-primary-600 mr-3" />
                    <h3 class="text-2xl font-semibold text-gray-900">Anagrafe e Stato Civile</h3>
                </div>
                
                <x-pub_theme::blocks.services.services-grid 
                    :services="[
                        ['title' => 'Certificati Anagrafici', 'description' => 'Richiedi e scarica certificati online', 'category' => 'anagrafe', 'status' => 'active', 'requiresAuth' => true],
                        ['title' => 'Iscrizione Anagrafica', 'description' => 'Iscrizione e variazione residenza', 'category' => 'anagrafe', 'status' => 'active', 'requiresAuth' => true],
                        ['title' => 'Stato Civile', 'description' => 'Atti di nascita, matrimonio, morte', 'category' => 'anagrafe', 'status' => 'active', 'requiresAuth' => true],
                        ['title' => 'Carta d\'Identità', 'description' => 'Richiesta e rinnovo carta d\'identità', 'category' => 'anagrafe', 'status' => 'active', 'requiresAuth' => true],
                    ]"
                    :columns="2"
                />
            </div>

            {{-- Tributi e Imposte --}}
            <div id="tributi" class="mb-16">
                <div class="flex items-center mb-8">
                    <x-heroicon-o-currency-euro class="w-8 h-8 text-primary-600 mr-3" />
                    <h3 class="text-2xl font-semibold text-gray-900">Tributi e Imposte</h3>
                </div>
                
                <x-pub_theme::blocks.services.services-grid 
                    :services="[
                        ['title' => 'Pagamento TASI', 'description' => 'Pagamento tassa sui servizi indivisibili', 'category' => 'tributi', 'status' => 'active'],
                        ['title' => 'Pagamento IMU', 'description' => 'Pagamento imposta municipale propria', 'category' => 'tributi', 'status' => 'active'],
                        ['title' => 'TARI - Tassa Rifiuti', 'description' => 'Pagamento tassa rifiuti', 'category' => 'tributi', 'status' => 'active'],
                        ['title' => 'Multe e Sanzioni', 'description' => 'Pagamento multe e sanzioni amministrative', 'category' => 'tributi', 'status' => 'active'],
                    ]"
                    :columns="2"
                />
            </div>

            {{-- Urbanistica e Edilizia --}}
            <div id="urbanistica" class="mb-16">
                <div class="flex items-center mb-8">
                    <x-heroicon-o-building-office class="w-8 h-8 text-primary-600 mr-3" />
                    <h3 class="text-2xl font-semibold text-gray-900">Urbanistica e Edilizia</h3>
                </div>
                
                <x-ui.services-grid 
                    :services="[
                        ['title' => 'SUAP Online', 'description' => 'Sportello Unico Attività Produttive', 'category' => 'urbanistica', 'status' => 'active', 'requiresAuth' => true],
                        ['title' => 'Permessi di Costruire', 'description' => 'Richiesta permessi edilizi', 'category' => 'urbanistica', 'status' => 'active', 'requiresAuth' => true],
                        ['title' => 'Certificazioni Urbanistiche', 'description' => 'Certificati di destinazione urbanistica', 'category' => 'urbanistica', 'status' => 'active', 'requiresAuth' => true],
                        ['title' => 'Mappa Catastale', 'description' => 'Consultazione mappe e cartografia', 'category' => 'urbanistica', 'status' => 'active'],
                    ]"
                    :columns="2"
                />
            </div>

            {{-- Servizi Sociali --}}
            <div id="sociale" class="mb-16">
                <div class="flex items-center mb-8">
                    <x-heroicon-o-heart class="w-8 h-8 text-primary-600 mr-3" />
                    <h3 class="text-2xl font-semibold text-gray-900">Servizi Sociali</h3>
                </div>
                
                <x-ui.services-grid 
                    :services="[
                        ['title' => 'Asili Nido', 'description' => 'Iscrizione e informazioni asili nido', 'category' => 'sociale', 'status' => 'active', 'requiresAuth' => true],
                        ['title' => 'Contributi Famiglie', 'description' => 'Richiesta contributi economici', 'category' => 'sociale', 'status' => 'active', 'requiresAuth' => true],
                        ['title' => 'Assistenza Anziani', 'description' => 'Servizi di assistenza domiciliare', 'category' => 'sociale', 'status' => 'active', 'requiresAuth' => true],
                        ['title' => 'Centri Sociali', 'description' => 'Attività e servizi centri sociali', 'category' => 'sociale', 'status' => 'active'],
                    ]"
                    :columns="2"
                />
            </div>

            {{-- Ambiente e Verde --}}
            <div id="ambiente">
                <div class="flex items-center mb-8">
                    {{--  
                    <x-heroicon-o-leaf class="w-8 h-8 text-primary-600 mr-3" />
                    --}}}
                    <h3 class="text-2xl font-semibold text-gray-900">Ambiente e Verde</h3>
                </div>
                
                <x-ui.services-grid 
                    :services="[
                        ['title' => 'Raccolta Differenziata', 'description' => 'Informazioni e calendario raccolta', 'category' => 'ambiente', 'status' => 'active'],
                        ['title' => 'Segnalazione Rifiuti', 'description' => 'Segnala abbandono rifiuti', 'category' => 'ambiente', 'status' => 'active'],
                        ['title' => 'Parchi e Giardini', 'description' => 'Informazioni aree verdi comunali', 'category' => 'ambiente', 'status' => 'active'],
                        ['title' => 'Inquinamento Acustico', 'description' => 'Segnalazione disturbi acustici', 'category' => 'ambiente', 'status' => 'active'],
                    ]"
                    :columns="2"
                />
            </div>
        </div>
    </section>

    {{-- How to Access Services --}}
    <section class="py-16 bg-white" aria-labelledby="access-heading">
        <div class="container-italia">
            <div class="text-center mb-12">
                <h2 id="access-heading" class="text-3xl font-bold text-gray-900 mb-4">Come Accedere ai Servizi</h2>
                <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                    Diverse modalità per utilizzare i servizi del Comune
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                {{-- Online --}}
                <div class="text-center p-6 bg-gray-50 rounded-lg">
                    <div class="w-16 h-16 bg-primary-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <x-heroicon-o-computer-desktop class="w-8 h-8 text-primary-600" />
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-3">Online</h3>
                    <p class="text-gray-600 mb-4">
                        Accedi ai servizi digitali 24/7 da computer, tablet o smartphone
                    </p>
                    <ul class="text-sm text-gray-600 space-y-1">
                        <li>• Servizi sempre disponibili</li>
                        <li>• Nessuna coda né attese</li>
                        <li>• Documenti immediati</li>
                    </ul>
                </div>

                {{-- Sportello --}}
                <div class="text-center p-6 bg-gray-50 rounded-lg">
                    <div class="w-16 h-16 bg-primary-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <x-heroicon-o-building-storefront class="w-8 h-8 text-primary-600" />
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-3">Sportello</h3>
                    <p class="text-gray-600 mb-4">
                        Presso gli uffici comunali con assistenza del personale
                    </p>
                    <ul class="text-sm text-gray-600 space-y-1">
                        <li>• Assistenza personalizzata</li>
                        <li>• Supporto per pratiche complesse</li>
                        <li>• Orari di apertura stabiliti</li>
                    </ul>
                </div>

                {{-- Telefono --}}
                <div class="text-center p-6 bg-gray-50 rounded-lg">
                    <div class="w-16 h-16 bg-primary-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <x-heroicon-o-phone class="w-8 h-8 text-primary-600" />
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-3">Telefono</h3>
                    <p class="text-gray-600 mb-4">
                        Contatta gli uffici per informazioni e supporto
                    </p>
                    <ul class="text-sm text-gray-600 space-y-1">
                        <li>• Assistenza telefonica</li>
                        <li>• Informazioni rapide</li>
                        <li>• Prenotazione appuntamenti</li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    {{-- Assistance Section --}}
    <section class="py-16 bg-primary-600 text-white">
        <div class="container-italia">
            <div class="max-w-4xl mx-auto text-center">
                <h2 class="text-3xl font-bold mb-6">Hai bisogno di aiuto?</h2>
                <p class="text-xl text-primary-100 mb-8">
                    Il nostro servizio di assistenza è a tua disposizione per supportarti
                </p>
                
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="{{ route('pages.view', ['slug' => 'assistenza']) }}" class="inline-flex items-center px-6 py-3 bg-white text-primary-600 font-semibold rounded-lg hover:bg-primary-50 transition-colors">
                        <x-heroicon-o-lifebuoy class="w-5 h-5 mr-2" />
                        Assistenza Online
                    </a>
                    
                    <a href="tel:+390612345678" class="inline-flex items-center px-6 py-3 border border-white text-white font-semibold rounded-lg hover:bg-white hover:text-primary-600 transition-colors">
                        <x-heroicon-o-phone class="w-5 h-5 mr-2" />
                        Chiama: 06 1234567
                    </a>
                    
                    <a href="{{ route('pages.view', ['slug' => 'urp']) }}" class="inline-flex items-center px-6 py-3 border border-white text-white font-semibold rounded-lg hover:bg-white hover:text-primary-600 transition-colors">
                        <x-heroicon-o-chat-bubble-left-right class="w-5 h-5 mr-2" />
                        Contatta URP
                    </a>
                </div>
            </div>
        </div>
    </section>

