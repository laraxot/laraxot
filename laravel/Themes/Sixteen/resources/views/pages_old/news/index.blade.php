<?php

declare(strict_types=1);

use function Laravel\Folio\name;

name('news');
?>

<x-layouts.main
    :title="__('pub_theme::news.meta.title')"
    :meta-description="__('pub_theme::news.meta.description')"
    :breadcrumb-title="__('pub_theme::news.meta.breadcrumb')"
>
    <section class="bg-primary-600 text-white py-12" aria-labelledby="news-heading">
        <div class="container-italia">
            <div class="max-w-4xl mx-auto text-center">
                <h1 id="news-heading" class="text-4xl font-bold mb-6">{{ __('pub_theme::news.hero.title') }}</h1>
                <p class="text-xl text-primary-100 mb-8">{{ __('pub_theme::news.hero.subtitle') }}</p>
                <nav class="flex flex-wrap justify-center gap-4" aria-label="{{ __('pub_theme::news.filters.aria') }}">
                    @foreach (['all', 'press', 'notices'] as $filter)
                        <a href="#news-{{ $filter }}" class="inline-flex items-center px-6 py-3 bg-white text-primary-600 font-semibold rounded-lg hover:bg-primary-50 transition-colors">
                            {{ __('pub_theme::news.filters.'.$filter) }}
                        </a>
                    @endforeach
                </nav>
            </div>
        </div>
    </section>

    <section id="news-all" class="py-16 bg-white" aria-labelledby="latest-news-heading">
        <div class="container-italia">
            <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center mb-12 gap-4">
                <div>
                    <h2 id="latest-news-heading" class="text-3xl font-bold text-gray-900 mb-2">{{ __('pub_theme::news.latest.title') }}</h2>
                    <p class="text-lg text-gray-600">{{ __('pub_theme::news.latest.subtitle') }}</p>
                </div>
                <label class="sr-only" for="news-search">{{ __('pub_theme::news.search.label') }}</label>
                <input id="news-search" type="search" placeholder="{{ __('pub_theme::news.search.placeholder') }}" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500">
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach (['one', 'two', 'three'] as $item)
                    <article class="bg-white rounded-lg shadow-sm border border-gray-200 p-6" id="news-{{ $item }}">
                        <div class="flex items-center justify-between mb-3 gap-3">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-primary-100 text-primary-800">{{ __('pub_theme::news.items.'.$item.'.category') }}</span>
                            <time datetime="{{ __('pub_theme::news.items.'.$item.'.date_iso') }}" class="text-sm text-gray-500">{{ __('pub_theme::news.items.'.$item.'.date') }}</time>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900 mb-3">{{ __('pub_theme::news.items.'.$item.'.title') }}</h3>
                        <p class="text-gray-600 mb-4">{{ __('pub_theme::news.items.'.$item.'.body') }}</p>
                        <a href="{{ url('/'.app()->getLocale().'/news') }}#news-{{ $item }}" class="inline-flex items-center text-primary-600 font-medium hover:text-primary-800">
                            {{ __('pub_theme::news.read_more') }}
                        </a>
                    </article>
                @endforeach
            </div>
        </div>
    </section>
</x-layouts.main>
