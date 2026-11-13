{{-- Homepage Template - Bootstrap Italia Design System --}}
<x-layouts.main 
    title="{{ __('pub_theme::legacy_homepage.homepage.page_title.label') }}"
    metaDescription="Portale istituzionale del Comune - Servizi digitali per i cittadini"
    :hideBreadcrumb="true">
    
    {{-- Hero Section --}}
    <section class="bg-primary-600 text-white py-16">
        <div class="container-italia">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                <div>
                    <h1 class="text-4xl lg:text-5xl font-bold mb-6 leading-tight">
                        {{ __('pub_theme::legacy_homepage.homepage.hero.title') }} {{ config('app.name') }}
                    </h1>
                    <p class="text-xl text-primary-100 mb-8 leading-relaxed">
                        {{ __('pub_theme::legacy_homepage.homepage.hero.intro') }}
                    </p>
                    <div class="flex flex-col sm:flex-row gap-4">
                        <a href="/servizi" class="btn btn-outline-primary bg-white text-primary-600 hover:bg-primary-50">
                            {{ __('pub_theme::legacy_homepage.homepage.hero.explore_services.label') }}
                        </a>
                        <a href="/contatti" class="btn btn-secondary bg-primary-700 hover:bg-primary-800 border-primary-700">
                            {{ __('pub_theme::legacy_homepage.homepage.hero.contact_office.label') }}
                        </a>
                        <x-bootstrap-italia.button
                            variant="outline-primary"
                            href="/servizi"
                            class="bg-white text-primary-600 hover:bg-primary-50">
                            {{ __('pub_theme::legacy_homepage.homepage.hero.explore_services.label') }}
                        </x-bootstrap-italia.button>
                        <x-bootstrap-italia.button
                            variant="secondary"
                            href="/contatti"
                            class="bg-primary-700 hover:bg-primary-800 border-primary-700">
                            {{ __('pub_theme::legacy_homepage.homepage.hero.contact_office.label') }}
                        </x-bootstrap-italia.button>
                    </div>
                </div>
                <div class="hidden lg:block">
                    <img src="/images/hero-illustration.svg" 
                         alt="{{ __('pub_theme::legacy_homepage.homepage.hero.image.label') }}" 
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
                    {{ __('pub_theme::legacy_homepage.homepage.featured.title') }}
                </h2>
                <p class="text-lg text-italia-gray-600 max-w-2xl mx-auto">
                    {{ __('pub_theme::legacy_homepage.homepage.featured.help') }}
                </p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                {{-- Servizio 1 --}}
                <x-blocks.cards.italia
                    title="{{ __('pub_theme::legacy_homepage.homepage.services.certificates.title') }}"
                    subtitle="Richiedi online"
                    hover
                    clickable
                    href="/servizi/certificati">
                    <p class="text-italia-gray-600 mb-4">
                        {{ __('pub_theme::legacy_homepage.homepage.services.certificates.help') }}
                    </p>
                    <div class="flex items-center text-primary-600 font-medium">
                        <span>{{ __('pub_theme::legacy_homepage.homepage.services.go_to_service.label') }}</span>
                        <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </div>
                </x-blocks.cards.italia>

                {{-- Servizio 2 --}}
                <x-blocks.cards.italia 
                    title="{{ __('pub_theme::legacy_homepage.homepage.services.tax_payments.title') }}"
                    subtitle="PagoPA"
                    hover
                    clickable
                    href="/servizi/pagamenti">
                    <p class="text-italia-gray-600 mb-4">
                        {{ __('pub_theme::legacy_homepage.homepage.services.tax_payments.help') }}
                    </p>
                    <div class="flex items-center text-primary-600 font-medium">
                        <span>{{ __('pub_theme::legacy_homepage.homepage.services.go_to_service.label') }}</span>
                        <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </div>
                </x-blocks.cards.italia>

                {{-- Servizio 3 --}}
                <x-blocks.cards.italia 
                    title="{{ __('pub_theme::legacy_homepage.homepage.services.building.title') }}"
                    subtitle="SUAP Online"
                    hover
                    clickable
                    href="/servizi/edilizia">
                    <p class="text-italia-gray-600 mb-4">
                        {{ __('pub_theme::legacy_homepage.homepage.services.building.help') }}
                    </p>
                    <div class="flex items-center text-primary-600 font-medium">
                        <span>{{ __('pub_theme::legacy_homepage.homepage.services.go_to_service.label') }}</span>
                        <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </div>
                </x-blocks.cards.italia>
            </div>
            
            <div class="text-center mt-10">
                <a class="btn btn-outline-primary" href="/servizi">
                    {{ __('pub_theme::navigation.homepage.all_services') }}
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
                        {{ __('pub_theme::legacy_homepage.homepage.news.title') }}
                    </h2>
                    <p class="text-italia-gray-600">
                        {{ __('pub_theme::legacy_homepage.homepage.news.help') }}
                    </p>
                </div>
                <a class="btn btn-outline-primary" href="/notizie">
                    {{ __('pub_theme::legacy_homepage.homepage.news.all.label') }}
                </a>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                {{-- Notizia 1 --}}
                <x-blocks.cards.italia
                    title="{{ __('pub_theme::legacy_homepage.homepage.news.opening_hours.title') }}"
                    image="/images/news-1.jpg"
                    imageAlt="Uffici comunali"
                    hover
                    clickable
                    href="/notizie/nuovi-orari-uffici">
                    <p class="text-sm text-italia-gray-500 mb-2">15 Gennaio 2024</p>
                    <p class="text-italia-gray-600">
                        {{ __('pub_theme::legacy_homepage.homepage.news.opening_hours.help') }}
                    </p>
                </x-blocks.cards.italia>

                {{-- Notizia 2 --}}
                <x-blocks.cards.italia 
                    title="{{ __('pub_theme::legacy_homepage.homepage.news.recycling.title') }}"
                    image="/images/news-2.jpg"
                    imageAlt="Raccolta differenziata"
                    hover
                    clickable
                    href="/notizie/raccolta-differenziata">
                    <p class="text-sm text-italia-gray-500 mb-2">12 Gennaio 2024</p>
                    <p class="text-italia-gray-600">
                        {{ __('pub_theme::legacy_homepage.homepage.news.recycling.help') }}
                    </p>
                </x-blocks.cards.italia>

                {{-- Notizia 3 --}}
                <x-blocks.cards.italia 
                    title="{{ __('pub_theme::legacy_homepage.homepage.news.family_grants.title') }}"
                    image="/images/news-3.jpg"
                    imageAlt="Contributi famiglie"
                    hover
                    clickable
                    href="/notizie/bando-contributi">
                    <p class="text-sm text-italia-gray-500 mb-2">10 Gennaio 2024</p>
                    <p class="text-italia-gray-600">
                        {{ __('pub_theme::legacy_homepage.homepage.news.family_grants.help') }}
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
                    {{ __('pub_theme::layouts_components.guest.help.title') }}
                </h2>
                <p class="text-xl text-primary-100 mb-8">
                    {{ __('pub_theme::legacy_homepage.homepage.help.message') }}
                </p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="/contatti" class="btn btn-outline-primary bg-white text-primary-600 hover:bg-primary-50">
                        {{ __('pub_theme::legacy_homepage.homepage.help.contact_offices.label') }}
                    </a>
                    <a href="tel:+390612345678" class="btn btn-secondary bg-primary-700 hover:bg-primary-800 border-primary-700">
                        {{ __('pub_theme::legacy_homepage.homepage.help.call.label') }} 06 1234567
                    </a>
                    <x-bootstrap-italia.button
                        variant="outline-primary"
                        href="/contatti"
                        class="bg-white text-primary-600 hover:bg-primary-50">
                        {{ __('pub_theme::legacy_homepage.homepage.help.contact_offices.label') }}
                    </x-bootstrap-italia.button>
                    <x-bootstrap-italia.button
                        variant="secondary"
                        href="tel:+390612345678"
                        class="bg-primary-700 hover:bg-primary-800 border-primary-700">
                        {{ __('pub_theme::legacy_homepage.homepage.help.call.label') }} 06 1234567
                    </x-bootstrap-italia.button>
                </div>
            </div>
        </div>
    </section>

</x-layouts.main>



