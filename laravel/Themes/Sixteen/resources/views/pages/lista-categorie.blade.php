<?php

declare(strict_types=1);

use function Laravel\Folio\name;

name('services.categories');

?>

{{--}}
{{-- 4-3-lista-categorie-template --}}
{{-- Design Comuni: pagina lista di secondo livello --}}
{{--}}

<x-layouts.app>
    <x-section slug="header" />

    <main id="main-content" class="max-w-7xl mx-auto px-4 py-8">
        {{-- Breadcrumb --}}
        <nav aria-label="Breadcrumb" class="mb-6">
            <ol class="flex items-center space-x-2 text-sm text-gray-600">
                <li>
                    <a href="{{ route('home') }}" class="hover:text-gray-900">{{ __('Home') }}</a>
                </li>
                <li class="before:content-['/'] before:mx-2">
                    <span class="text-gray-900 font-medium">{{ __('Categorie') }}</span>
                </li>
            </ol>
        </nav>

        {{-- Page title --}}
        <h1 class="text-3xl font-bold mb-8">{{ __('Categorie di servizi') }}</h1>

        {{-- Highlighted categories section --}}
        <section class="mb-12">
            <h2 class="text-xl font-semibold mb-6">{{ __('Categorie in evidenza') }}</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-white border border-gray-200 rounded-lg p-6 hover:shadow-lg transition-shadow">
                    <h3 class="text-lg font-semibold mb-3">
                        <a href="#" class="text-gray-900 hover:text-blue-600 no-underline flex items-center justify-between">
                            {{ __('Anagrafe e stato civile') }}
                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </a>
                    </h3>
                    <p class="text-gray-600 text-sm">{{ __('Servizi relativi a certificati, residenza e pratiche anagrafiche') }}</p>
                </div>
                <div class="bg-white border border-gray-200 rounded-lg p-6 hover:shadow-lg transition-shadow">
                    <h3 class="text-lg font-semibold mb-3">
                        <a href="#" class="text-gray-900 hover:text-blue-600 no-underline flex items-center justify-between">
                            {{ __('Tributi e pagamenti') }}
                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </a>
                    </h3>
                    <p class="text-gray-600 text-sm">{{ __('IMU, TARI, tasse e pagamenti verso il Comune') }}</p>
                </div>
                <div class="bg-white border border-gray-200 rounded-lg p-6 hover:shadow-lg transition-shadow">
                    <h3 class="text-lg font-semibold mb-3">
                        <a href="#" class="text-gray-900 hover:text-blue-600 no-underline flex items-center justify-between">
                            {{ __('Edilizia e urbanistica') }}
                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </a>
                    </h3>
                    <p class="text-gray-600 text-sm">{{ __('Permessi di costruire, SCIA, pratiche edilizie') }}</p>
                </div>
            </div>
        </section>

        {{-- All categories section --}}
        <section>
            <h2 class="text-xl font-semibold mb-6">{{ __('Tutte le categorie') }}</h2>
            <div class="divide-y divide-gray-200">
                <a href="#" class="block py-4 hover:bg-gray-50 flex items-center justify-between group">
                    <span class="text-gray-900 group-hover:text-blue-600">{{ __('Ambiente e territorio') }}</span>
                    <svg class="w-5 h-5 text-gray-400 group-hover:text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </a>
                <a href="#" class="block py-4 hover:bg-gray-50 flex items-center justify-between group">
                    <span class="text-gray-900 group-hover:text-blue-600">{{ __('Anagrafe e stato civile') }}</span>
                    <svg class="w-5 h-5 text-gray-400 group-hover:text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </a>
                <a href="#" class="block py-4 hover:bg-gray-50 flex items-center justify-between group">
                    <span class="text-gray-900 group-hover:text-blue-600">{{ __('Artigianato, commercio e imprese') }}</span>
                    <svg class="w-5 h-5 text-gray-400 group-hover:text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </a>
                <a href="#" class="block py-4 hover:bg-gray-50 flex items-center justify-between group">
                    <span class="text-gray-900 group-hover:text-blue-600">{{ __('Cultura, sport e tempo libero') }}</span>
                    <svg class="w-5 h-5 text-gray-400 group-hover:text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </a>
                <a href="#" class="block py-4 hover:bg-gray-50 flex items-center justify-between group">
                    <span class="text-gray-900 group-hover:text-blue-600">{{ __('Edilizia e urbanistica') }}</span>
                    <svg class="w-5 h-5 text-gray-400 group-hover:text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </a>
                <a href="#" class="block py-4 hover:bg-gray-50 flex items-center justify-between group">
                    <span class="text-gray-900 group-hover:text-blue-600">{{ __('Elezioni e cittadinanza') }}</span>
                    <svg class="w-5 h-5 text-gray-400 group-hover:text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </a>
                <a href="#" class="block py-4 hover:bg-gray-50 flex items-center justify-between group">
                    <span class="text-gray-900 group-hover:text-blue-600">{{ __('Famiglia, sociale e salute') }}</span>
                    <svg class="w-5 h-5 text-gray-400 group-hover:text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </a>
                <a href="#" class="block py-4 hover:bg-gray-50 flex items-center justify-between group">
                    <span class="text-gray-900 group-hover:text-blue-600">{{ __('Istruzione e formazione') }}</span>
                    <svg class="w-5 h-5 text-gray-400 group-hover:text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </a>
                <a href="#" class="block py-4 hover:bg-gray-50 flex items-center justify-between group">
                    <span class="text-gray-900 group-hover:text-blue-600">{{ __('Lavoro e fisco') }}</span>
                    <svg class="w-5 h-5 text-gray-400 group-hover:text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </a>
                <a href="#" class="block py-4 hover:bg-gray-50 flex items-center justify-between group">
                    <span class="text-gray-900 group-hover:text-blue-600">{{ __('Polizia locale e sicurezza') }}</span>
                    <svg class="w-5 h-5 text-gray-400 group-hover:text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </a>
                <a href="#" class="block py-4 hover:bg-gray-50 flex items-center justify-between group">
                    <span class="text-gray-900 group-hover:text-blue-600">{{ __('Tributi e pagamenti') }}</span>
                    <svg class="w-5 h-5 text-gray-400 group-hover:text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </a>
                <a href="#" class="block py-4 hover:bg-gray-50 flex items-center justify-between group">
                    <span class="text-gray-900 group-hover:text-blue-600">{{ __('Trasporti e mobilità') }}</span>
                    <svg class="w-5 h-5 text-gray-400 group-hover:text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </a>
            </div>
        </section>
    </main>

    <x-section slug="footer" />
</x-layouts.app>
