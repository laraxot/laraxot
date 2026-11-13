<?php

declare(strict_types=1);

use function Laravel\Folio\name;

name('administration');
?>

<x-layouts.main
    :title="__('pub_theme::administration.meta.title')"
    :meta-description="__('pub_theme::administration.meta.description')"
    :breadcrumb-title="__('pub_theme::administration.meta.breadcrumb')"
>
    <section class="bg-primary-600 text-white py-12" aria-labelledby="administration-heading">
        <div class="container-italia max-w-5xl mx-auto text-center">
            <h1 id="administration-heading" class="text-4xl font-bold mb-6">{{ __('pub_theme::administration.hero.title') }}</h1>
            <p class="text-xl text-primary-100 mb-8">{{ __('pub_theme::administration.hero.subtitle') }}</p>
            <nav class="flex flex-wrap justify-center gap-4" aria-label="{{ __('pub_theme::administration.sections.aria') }}">
                @foreach (['organisation', 'documents', 'data'] as $section)
                    <a href="#{{ $section }}" class="inline-flex items-center px-6 py-3 bg-white text-primary-600 font-semibold rounded-lg hover:bg-primary-50 transition-colors">
                        {{ __('pub_theme::administration.sections.'.$section) }}
                    </a>
                @endforeach
            </nav>
        </div>
    </section>

    <main class="py-16 bg-white">
        <div class="container-italia">
            @foreach (['organisation', 'documents', 'data'] as $section)
                <section id="{{ $section }}" class="mb-16" aria-labelledby="{{ $section }}-heading">
                    <header class="text-center mb-10">
                        <h2 id="{{ $section }}-heading" class="text-3xl font-bold text-gray-900 mb-3">{{ __('pub_theme::administration.'.$section.'.title') }}</h2>
                        <p class="text-lg text-gray-600 max-w-2xl mx-auto">{{ __('pub_theme::administration.'.$section.'.subtitle') }}</p>
                    </header>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        @foreach (['first', 'second', 'third'] as $card)
                            <article class="bg-gray-50 rounded-lg p-6 border border-gray-200">
                                <h3 class="text-xl font-semibold text-gray-900 mb-3">{{ __('pub_theme::administration.'.$section.'.cards.'.$card.'.title') }}</h3>
                                <p class="text-gray-600 mb-4">{{ __('pub_theme::administration.'.$section.'.cards.'.$card.'.body') }}</p>
                                <a href="{{ url('/'.app()->getLocale().'/administration') }}#{{ $section }}" class="inline-flex items-center text-primary-600 font-medium hover:text-primary-800">
                                    {{ __('pub_theme::administration.more') }}
                                </a>
                            </article>
                        @endforeach
                    </div>
                </section>
            @endforeach
        </div>
    </main>
</x-layouts.main>
