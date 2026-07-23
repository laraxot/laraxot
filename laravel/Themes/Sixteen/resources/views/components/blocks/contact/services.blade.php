{{-- Contact & Services Section - Design Comuni Style --}}
@props([
    'contact_title' => 'Contatta il Comune',
    'report_title' => 'Problemi in città',
])

<section class="py-8 bg-light">
    <div class="container">
        <div class="row g-4">
            {{-- Contact Box --}}
            <div class="col-12 col-md-6 col-lg-4">
                <div class="card card-teaser shadow-sm h-100">
                    <div class="card-body p-4">
                        <h3 class="h5 mb-3">{{ $contact_title }}</h3>
                        
                        <ul class="list-unstyled mb-4">
                            <li class="mb-2">
                                <a href="#" class="text-decoration-none">
                                    <x-filament::icon icon="heroicon-o-document-text" class="w-4 h-4 inline me-2" />
                                    Leggi le FAQ
                                </a>
                            </li>
                            <li class="mb-2">
                                <a href="#" class="text-decoration-none">
                                    <x-filament::icon icon="heroicon-o-lifebuoy" class="w-4 h-4 inline me-2" />
                                    Richiedi assistenza
                                </a>
                            </li>
                            <li class="mb-2">
                                <a href="tel:061234567" class="text-decoration-none">
                                    <x-filament::icon icon="heroicon-o-phone" class="w-4 h-4 inline me-2" />
                                    Chiama il numero verde
                                </a>
                            </li>
                            <li class="mb-2">
                                <a href="#" class="text-decoration-none">
                                    <x-filament::icon icon="heroicon-o-calendar" class="w-4 h-4 inline me-2" />
                                    Prenota appuntamento
                                </a>
                            </li>
                        </ul>
                        
                        <a href="#" class="btn btn-outline-primary w-100">
                            Tutti i contatti
                        </a>
                    </div>
                </div>
            </div>
            
            {{-- Reporting Box --}}
            <div class="col-12 col-md-6 col-lg-4">
                <div class="card card-teaser shadow-sm h-100">
                    <div class="card-body p-4">
                        <h3 class="h5 mb-3">{{ $report_title }}</h3>
                        
                        <p class="text-muted mb-4">
                            Segnala un disservizio o un problema sul territorio
                        </p>
                        
                        <a href="#" class="btn btn-outline-primary w-100">
                            <x-filament::icon icon="heroicon-o-exclamation-triangle" class="w-5 h-5 me-2" />
                            Segnala disservizio
                        </a>
                    </div>
                </div>
            </div>
            
            {{-- Secondary Search --}}
            <div class="col-12 col-md-6 col-lg-4">
                <div class="card card-teaser shadow-sm h-100">
                    <div class="card-body p-4">
                        <h3 class="h5 mb-3">Forse stavi cercando</h3>
                        
                        <ul class="list-unstyled mb-4">
                            <li class="mb-2">
                                <a href="#" class="text-decoration-none text-primary">
                                    <x-filament::icon icon="heroicon-o-document-arrow-down" class="w-4 h-4 inline me-2" />
                                    Rilascio CIE
                                </a>
                            </li>
                            <li class="mb-2">
                                <a href="#" class="text-decoration-none text-primary">
                                    <x-filament::icon icon="heroicon-o-home" class="w-4 h-4 inline me-2" />
                                    Cambio di residenza
                                </a>
                            </li>
                            <li class="mb-2">
                                <a href="#" class="text-decoration-none text-primary">
                                    <x-filament::icon icon="heroicon-o-currency-euro" class="w-4 h-4 inline me-2" />
                                    Tributi online
                                </a>
                            </li>
                            <li class="mb-2">
                                <a href="#" class="text-decoration-none text-primary">
                                    <x-filament::icon icon="heroicon-o-calendar" class="w-4 h-4 inline me-2" />
                                    Prenotazione appuntamenti
                                </a>
                            </li>
                            <li class="mb-2">
                                <a href="#" class="text-decoration-none text-primary">
                                    <x-filament::icon icon="heroicon-o-identification" class="w-4 h-4 inline me-2" />
                                    Rilascio tessera elettorale
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
