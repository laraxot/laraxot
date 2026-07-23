{{-- Home Page Template - AGID Compliant --}}
{{-- Template istituzionale per home page di enti pubblici --}}

@extends('layouts.marketing')

@section('title', config('sixteen.app.name') . ' - ' . config('sixteen.app.tagline'))

@section('content')
    {{-- Hero Section --}}
    <section class="hero-section bg-italia-blue-600 text-white py-16">
        <div class="container mx-auto px-4">
            <div class="flex flex-col lg:flex-row items-center justify-between">
                <div class="lg:w-1/2 mb-8 lg:mb-0">
                    <h1 class="text-4xl lg:text-5xl font-bold mb-4">
                        {{ config('sixteen.app.name') }}
                    </h1>
                    <p class="text-xl mb-6">
                        {{ config('sixteen.app.description') }}
                    </p>
                    
                    {{-- Quick Actions --}}
                    <div class="flex flex-wrap gap-4">
                        <x-button 
                        <x-button 
                            href="{{ route('services') }}"
                            variant="primary"
                            size="lg">
                            Servizi Online
                        </x-button>
                        
                        <x-button 
                        </x-button>
                        
                        <x-button 
                            href="{{ route('news') }}"
                            variant="outline"
                            size="lg">
                            Notizie
                        </x-button>
                        </x-button>
                    </div>
                </div>
                
                <div class="lg:w-1/2">
                    {{-- Hero Image/Illustration --}}
                    <div class="bg-white/20 rounded-lg p-8">
                        <div class="text-center">
                            <x-filament::icon icon="heroicon-o-building-office-2" class="w-24 h-24 mx-auto mb-4" />
f7ac8eda (.)
                            <p class="text-sm">Servizi digitali per i cittadini</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Featured Services --}}
    <section class="py-16 bg-gray-50">
        <div class="container mx-auto px-4">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-gray-900 mb-4">Servizi in Evidenza</h2>
                <p class="text-lg text-gray-600">Accedi rapidamente ai servizi più richiesti</p>
            </div>
            
            <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-6">
                {{-- Service Cards --}}
                <x-blocks.cards.service 
                <x-blocks.cards.service 
                    title="Anagrafe"
                    description="Certificati e documenti anagrafici"
                    icon="heroicon-o-identification"
                    href="{{ route('services.anagrafe') }}"
                    variant="primary" />
                
                <x-blocks.cards.service 
                <x-blocks.cards.service 
                    title="Tributi"
                    description="Pagamento tasse e imposte"
                    icon="heroicon-o-credit-card"
                    href="{{ route('services.tributi') }}"
                    variant="primary" />
                
                <x-blocks.cards.service 
                <x-blocks.cards.service 
                    title="SUAP"
                    description="Sportello Unico Attività Produttive"
                    icon="heroicon-o-building-storefront"
                    href="{{ route('services.suap') }}"
                    variant="primary" />
                
                <x-blocks.cards.service 
                <x-blocks.cards.service 
                    title="Segnalazioni"
                    description="Segnala problemi e disservizi"
                    icon="heroicon-o-chat-bubble-left-ellipsis"
                    href="{{ route('services.segnalazioni') }}"
                    variant="primary" />
            </div>
            
            <div class="text-center mt-8">
                <x-button 
                    href="{{ route('services') }}"
                    variant="secondary">
                    Tutti i Servizi
                </x-button>
            </div>
        </div>
    </section>

    {{-- Latest News --}}
    <section class="py-16">
        <div class="container mx-auto px-4">
            <div class="flex justify-between items-center mb-8">
                <h2 class="text-3xl font-bold text-gray-900">Ultime Notizie</h2>
                <x-button 
                    href="{{ route('news') }}"
                    variant="link">
                    Vedi tutte
                </x-button>
            </div>
            
            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
                {{-- News Cards --}}
                @foreach($latestNews as $news)
                    <x-blocks.cards.news-card 
                    <x-blocks.cards.news-card 
                        :title="$news->title"
                        :href="route('news.show', $news)"
                        :date="$news->published_at"
                        :category="$news->category?->name"
                        :excerpt="$news->excerpt"
                        :image="$news->image_url"
                        image-alt="{{ $news->title }}" />
                @endforeach
            </div>
        </div>
    </section>

    {{-- Upcoming Events --}}
    <section class="py-16 bg-gray-50">
        <div class="container mx-auto px-4">
            <div class="flex justify-between items-center mb-8">
                <h2 class="text-3xl font-bold text-gray-900">Prossimi Eventi</h2>
                <x-button 
                    href="{{ route('events') }}"
                    variant="link">
                    Calendario completo
                </x-button>
            </div>
            
            <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-6">
                {{-- Event Cards --}}
                @foreach($upcomingEvents as $event)
                    <x-blocks.cards.event-card 
                    <x-blocks.cards.event-card 
                        :title="$event->title"
                        :href="route('events.show', $event)"
                        :date="$event->start_date"
                        :location="$event->location"
                        :category="$event->category?->name" />
                @endforeach
            </div>
        </div>
    </section>

    {{-- Transparency Section --}}
    <section class="py-16">
        <div class="container mx-auto px-4">
            <h2 class="text-3xl font-bold text-gray-900 mb-8 text-center">Amministrazione Trasparente</h2>
            
            <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-6">
                <x-blocks.cards.basic 
                <x-blocks.cards.basic 
                    title="Albo Pretorio"
                    description="Atti e documenti ufficiali"
                    icon="heroicon-o-document-text"
                    href="{{ route('transparency.albo-pretorio') }}" />
                
                <x-blocks.cards.basic 
                <x-blocks.cards.basic 
                    title="Bandi di Gara"
                    description="Concorsi e appalti pubblici"
                    icon="heroicon-o-clipboard-document-list"
                    href="{{ route('transparency.bandi') }}" />
                
                <x-blocks.cards.basic 
                <x-blocks.cards.basic 
                    title="Bilanci"
                    description="Documenti contabili e finanziari"
                    icon="heroicon-o-calculator"
                    href="{{ route('transparency.bilanci') }}" />
                
                <x-blocks.cards.basic 
                <x-blocks.cards.basic 
                    title="Statistiche"
                    description="Dati e indicatori dell'ente"
                    icon="heroicon-o-chart-bar"
                    href="{{ route('transparency.statistiche') }}" />
            </div>
        </div>
    </section>

    {{-- Emergency/Important Notices --}}
    @if($emergencyNotices->isNotEmpty())
        <section class="py-8 bg-yellow-50 border-y border-yellow-200">
            <div class="container mx-auto px-4">
                <h3 class="text-lg font-semibold text-yellow-800 mb-4">Comunicazioni Importanti</h3>
                <div class="space-y-2">
                    @foreach($emergencyNotices as $notice)
                        <div class="flex items-start">
                            <x-filament::icon icon="heroicon-o-exclamation-triangle" class="w-5 h-5 text-yellow-600 mt-0.5 mr-3 flex-shrink-0" />
f7ac8eda (.)
                            <div>
                                <p class="text-yellow-800 font-medium">{{ $notice->title }}</p>
                                @if($notice->description)
                                    <p class="text-yellow-700 text-sm">{{ $notice->description }}</p>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif
@endsection

@section('scripts')
    {{-- Home page specific scripts --}}
    <script>
        // Initialize any home page specific functionality
        document.addEventListener('DOMContentLoaded', function() {
            // Example: Smooth scrolling for anchor links
            document.querySelectorAll('a[href^="#"]').forEach(anchor => {
                anchor.addEventListener('click', function (e) {
                    e.preventDefault();
                    const target = document.querySelector(this.getAttribute('href'));
                    if (target) {
                        target.scrollIntoView({
                            behavior: 'smooth',
                            block: 'start'
                        });
                    }
                });
            });
        });
    </script>
@endsection
