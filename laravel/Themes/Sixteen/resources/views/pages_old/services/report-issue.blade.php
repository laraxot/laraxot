<?php

declare(strict_types=1);

use function Laravel\Folio\name;

name('services.report-issue');
?>

<x-layouts.app
    :title="__('pub_theme::service_report.meta.title')"
    :metaDescription="__('pub_theme::service_report.meta.description')"
    :breadcrumbTitle="__('pub_theme::service_report.meta.breadcrumb')"
>
    <main class="container-italia mx-auto px-4 py-10" aria-labelledby="service-report-title">
        <nav class="mb-8 text-sm" aria-label="{{ __('pub_theme::service_report.breadcrumb.aria') }}">
            <ol class="flex flex-wrap items-center gap-2 text-gray-700">
                <li><a class="underline" href="{{ \Mcamara\LaravelLocalization\Facades\LaravelLocalization::localizeURL('/') }}">{{ __('pub_theme::service_report.breadcrumb.home') }}</a></li>
                <li aria-hidden="true">/</li>
                <li><a class="underline" href="{{ \Mcamara\LaravelLocalization\Facades\LaravelLocalization::localizeURL('/services') }}">{{ __('pub_theme::service_report.breadcrumb.services') }}</a></li>
                <li aria-hidden="true">/</li>
                <li aria-current="page">{{ __('pub_theme::service_report.breadcrumb.current') }}</li>
            </ol>
        </nav>

        <div class="grid gap-10 lg:grid-cols-[minmax(0,2fr)_minmax(18rem,1fr)]">
            <article>
                <p class="mb-3 font-semibold uppercase tracking-wide text-primary-700">{{ __('pub_theme::service_report.eyebrow') }}</p>
                <h1 id="service-report-title" class="mb-5 text-4xl font-bold text-gray-900">{{ __('pub_theme::service_report.hero.title') }}</h1>
                <p class="mb-8 text-xl leading-relaxed text-gray-700">{{ __('pub_theme::service_report.hero.description') }}</p>

                <section class="mb-8" aria-labelledby="who-title">
                    <h2 id="who-title" class="mb-3 text-2xl font-bold text-gray-900">{{ __('pub_theme::service_report.sections.who.title') }}</h2>
                    <p class="text-gray-700">{{ __('pub_theme::service_report.sections.who.body') }}</p>
                </section>

                <section class="mb-8" aria-labelledby="how-title">
                    <h2 id="how-title" class="mb-3 text-2xl font-bold text-gray-900">{{ __('pub_theme::service_report.sections.how.title') }}</h2>
                    <ol id="report-process-steps" class="list-decimal space-y-3 pl-6 text-gray-700">
                        @foreach (['one', 'two', 'three', 'four', 'five'] as $step)
                            <li>{{ __('pub_theme::service_report.sections.how.steps.'.$step) }}</li>
                        @endforeach
                    </ol>
                </section>

                <section class="mb-8 grid gap-6 sm:grid-cols-2" aria-label="{{ __('pub_theme::service_report.details.aria') }}">
                    @foreach (['requirements', 'outcome', 'duration', 'cost'] as $detail)
                        <div class="rounded-lg border border-gray-200 bg-gray-50 p-5">
                            <h2 class="mb-2 text-lg font-semibold text-gray-900">{{ __('pub_theme::service_report.details.'.$detail.'.title') }}</h2>
                            <p class="text-gray-700">{{ __('pub_theme::service_report.details.'.$detail.'.body') }}</p>
                        </div>
                    @endforeach
                </section>
            </article>

            <aside class="h-fit rounded-xl border border-gray-200 bg-white p-6 shadow-sm" aria-labelledby="access-title">
                <h2 id="access-title" class="mb-3 text-2xl font-bold text-gray-900">{{ __('pub_theme::service_report.access.title') }}</h2>
                <p class="mb-6 text-gray-700">{{ __('pub_theme::service_report.access.body') }}</p>
                <a href="{{ \Mcamara\LaravelLocalization\Facades\LaravelLocalization::localizeURL('/tickets/create') }}" class="inline-flex min-h-11 w-full items-center justify-center rounded-lg bg-primary-700 px-5 py-3 text-center font-semibold text-white hover:bg-primary-800 focus:outline-none focus:ring-2 focus:ring-primary-700">
                    {{ __('pub_theme::service_report.access.cta') }}
                </a>
                <a href="{{ \Mcamara\LaravelLocalization\Facades\LaravelLocalization::localizeURL('/tickets') }}" class="mt-3 inline-flex min-h-11 w-full items-center justify-center rounded-lg border border-primary-700 px-5 py-3 text-center font-semibold text-primary-700 underline underline-offset-4 hover:bg-primary-50 focus:outline-none focus:ring-2 focus:ring-primary-700">
                    {{ __('pub_theme::service_report.access.all_reports') }}
                </a>
                <p class="mt-4 text-sm text-gray-600">{{ __('pub_theme::service_report.access.note') }}</p>
            </aside>
        </div>
    </main>
</x-layouts.app>
