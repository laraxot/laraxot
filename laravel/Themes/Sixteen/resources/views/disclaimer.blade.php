@extends('themes::Sixteen.layouts.guest')

@php
    $institution = config('app.institution_name', 'Ente di appartenenza');
@endphp

@section('title', __('disclaimer.title'))

@section('content')
<div class="container mx-auto px-4 py-12">
    <div class="max-w-4xl mx-auto">
        <header class="mb-12">
            <h1 class="text-4xl font-bold text-gray-900 dark:text-gray-100 mb-4">{{ __('disclaimer.title') }}</h1>
            <p class="text-xl text-gray-600 dark:text-gray-300">{{ __('disclaimer.subtitle') }}</p>
        </header>

        <article class="prose dark:prose-invert max-w-none">
            <section class="mb-8">
                <h2>{{ __('disclaimer.purpose') }}</h2>
                <p>{{ __('disclaimer.purpose_text') }}</p>
            </section>

            <section class="mb-8">
                <h2>{{ __('disclaimer.legal_notices') }}</h2>
                <h3>{{ __('disclaimer.ownership') }}</h3>
                <p>{{ __('disclaimer.ownership_text', ['institution' => $institution]) }}</p>

                <h3>{{ __('disclaimer.ip_rights') }}</h3>
                <p>{{ __('disclaimer.ip_rights_text') }}</p>

                <h3>{{ __('disclaimer.restrictions') }}</h3>
                <ul>
                    <li>{{ __('disclaimer.restriction_personal') }}</li>
                    <li>{{ __('disclaimer.restriction_no_copy') }}</li>
                    <li>{{ __('disclaimer.restriction_no_derivative') }}</li>
                    <li>{{ __('disclaimer.restriction_illegal') }}</li>
                </ul>
            </section>

            <section class="mb-8">
                <h2>{{ __('disclaimer.limitation_liability') }}</h2>
                <p>{{ __('disclaimer.limitation_text') }}</p>
            </section>

            <section class="mb-8">
                <h2>{{ __('disclaimer.external_links') }}</h2>
                <p>{{ __('disclaimer.external_links_text') }}</p>
            </section>

            <section class="mb-8">
                <h2>{{ __('disclaimer.changes') }}</h2>
                <p>{{ __('disclaimer.changes_text') }}</p>
            </section>

            <section class="mb-8">
                <h2>{{ __('disclaimer.governing_law') }}</h2>
                <p>{{ __('disclaimer.governing_law_text') }}</p>
            </section>

            <section class="mb-8">
                <h2>{{ __('disclaimer.contact') }}</h2>
                <address class="not-italic">
                    <p><strong>{{ $institution }}</strong></p>
                    <p>{{ config('app.institution_address', 'Indirizzo dell\'ente') }}</p>
                    <p>{{ config('app.institution_email', 'email@ente.it') }}</p>
                    <p>{{ config('app.institution_pec', 'pec@ente.it') }}</p>
                    <p>{{ config('app.institution_phone', '+39 0XX XXXXXXX') }}</p>
                </address>
            </section>
        </article>

        <footer class="mt-12 pt-8 border-t border-gray-200 dark:border-gray-700">
            <p class="text-sm text-gray-500 dark:text-gray-400">
                {{ __('disclaimer.last_updated', ['date' => now()->format('d/m/Y')]) }}
            </p>
        </footer>
    </div>
</div>
@endsection