{{-- Fixcity civic services entry point. All public copy belongs to locale files. --}}
<x-layouts.app
    :title="__('pub_theme::services.meta.title')"
    :metaDescription="__('pub_theme::services.meta.description')"
    :breadcrumbTitle="__('pub_theme::services.meta.breadcrumb')"
>
    <div
        x-data="{
            query: '',
            get resultCount() {
                const search = this.query.trim().toLocaleLowerCase();
                return [...this.$refs.catalogue.querySelectorAll('[data-service-searchable]')]
                    .filter((item) => item.dataset.search.includes(search)).length;
            }
        }"
    >
        <section id="fixcity-services-hero" class="bg-white py-10 text-gray-900" aria-labelledby="services-title">
            <div class="container-italia mx-auto px-4">
                <div class="mx-auto max-w-4xl text-center">
                    <h1 id="services-title" class="mb-5 text-4xl font-bold text-gray-900">{{ __('pub_theme::services.hero.title') }}</h1>
                    <p class="mb-8 text-xl text-gray-700">{{ __('pub_theme::services.hero.subtitle') }}</p>
                    <label for="service-search" class="sr-only">{{ __('pub_theme::services.hero.search_label') }}</label>
                    <input
                        id="service-search"
                        type="search"
                        x-model.debounce.150ms="query"
                        aria-controls="service-catalogue"
                        placeholder="{{ __('pub_theme::services.hero.search_placeholder') }}"
                        class="w-full rounded-lg border border-gray-300 bg-white px-5 py-4 text-gray-900 focus:border-primary-700 focus:outline-none focus:ring-2 focus:ring-primary-700"
                    >
                    <p class="mt-3 text-sm text-gray-700" role="status" aria-live="polite" aria-atomic="true">
                        <span x-text="resultCount"></span>
                        <span x-text="resultCount === 1 ? @js(__('pub_theme::services.results.singular')) : @js(__('pub_theme::services.results.plural'))"></span>
                    </p>
                </div>
            </div>
        </section>

        <section class="border-b border-gray-200 bg-gray-50 py-6" aria-label="{{ __('pub_theme::services.nav.aria') }}">
            <nav class="container-italia mx-auto grid grid-cols-1 justify-center gap-3 px-4 sm:flex sm:flex-wrap">
                <a href="#report" class="inline-flex min-h-11 w-full items-center justify-center rounded-lg border border-gray-300 bg-white px-5 py-3 text-center font-semibold text-gray-900 hover:border-primary-600 focus:outline-none focus:ring-2 focus:ring-primary-600 sm:w-auto">{{ __('pub_theme::services.nav.report') }}</a>
                <a href="#browse" class="inline-flex min-h-11 w-full items-center justify-center rounded-lg border border-gray-300 bg-white px-5 py-3 text-center font-semibold text-gray-900 hover:border-primary-600 focus:outline-none focus:ring-2 focus:ring-primary-600 sm:w-auto">{{ __('pub_theme::services.nav.browse') }}</a>
                <a href="#track" class="inline-flex min-h-11 w-full items-center justify-center rounded-lg border border-gray-300 bg-white px-5 py-3 text-center font-semibold text-gray-900 hover:border-primary-600 focus:outline-none focus:ring-2 focus:ring-primary-600 sm:w-auto">{{ __('pub_theme::services.nav.track') }}</a>
            </nav>
        </section>

        <section id="service-catalogue" x-ref="catalogue" class="container-italia mx-auto px-4 py-12" aria-labelledby="catalogue-title">
            <div class="mb-8 max-w-3xl">
                <h2 id="catalogue-title" class="mb-3 text-3xl font-bold text-gray-900">{{ __('pub_theme::services.featured.title') }}</h2>
                <p class="text-lg text-gray-700">{{ __('pub_theme::services.featured.subtitle') }}</p>
            </div>

            <div class="grid grid-cols-1 gap-6 md:grid-cols-3">
                <article id="report" data-service-searchable data-search="{{ mb_strtolower(__('pub_theme::services.tasks.report_title').' '.__('pub_theme::services.tasks.report_body').' '.__('pub_theme::services.tasks.report_auth_note')) }}" x-show="!query.trim() || $el.dataset.search.includes(query.trim().toLocaleLowerCase())" class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
                    <x-heroicon-o-exclamation-triangle class="mb-4 h-8 w-8 text-primary-700" aria-hidden="true" />
                    <h3 class="mb-2 text-xl font-semibold text-gray-900">{{ __('pub_theme::services.tasks.report_title') }}</h3>
                    <p class="mb-5 text-gray-700">{{ __('pub_theme::services.tasks.report_body') }}</p>
                    <p class="mb-5 text-sm text-gray-700">{{ __('pub_theme::services.tasks.report_auth_note') }}</p>
                    <a href="{{ \Mcamara\LaravelLocalization\Facades\LaravelLocalization::localizeURL('/services/report-issue') }}" class="inline-flex min-h-11 items-center font-semibold text-primary-700 underline underline-offset-4 focus:outline-none focus:ring-2 focus:ring-primary-700">{{ __('pub_theme::services.tasks.report_action') }} <span aria-hidden="true" class="ml-2">→</span></a>
                </article>

                <article id="browse" data-service-searchable data-search="{{ mb_strtolower(__('pub_theme::services.tasks.browse_title').' '.__('pub_theme::services.tasks.browse_body')) }}" x-show="!query.trim() || $el.dataset.search.includes(query.trim().toLocaleLowerCase())" class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
                    <x-heroicon-o-map class="mb-4 h-8 w-8 text-primary-700" aria-hidden="true" />
                    <h3 class="mb-2 text-xl font-semibold text-gray-900">{{ __('pub_theme::services.tasks.browse_title') }}</h3>
                    <p class="mb-5 text-gray-700">{{ __('pub_theme::services.tasks.browse_body') }}</p>
                    <a href="{{ \Mcamara\LaravelLocalization\Facades\LaravelLocalization::localizeURL('/tickets') }}" class="inline-flex min-h-11 items-center font-semibold text-primary-700 underline underline-offset-4 focus:outline-none focus:ring-2 focus:ring-primary-700">{{ __('pub_theme::services.tasks.browse_action') }} <span aria-hidden="true" class="ml-2">→</span></a>
                </article>

                <article id="track" data-service-searchable data-search="{{ mb_strtolower(__('pub_theme::services.tasks.track_title').' '.__('pub_theme::services.tasks.track_body')) }}" x-show="!query.trim() || $el.dataset.search.includes(query.trim().toLocaleLowerCase())" class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
                    <x-heroicon-o-magnifying-glass class="mb-4 h-8 w-8 text-primary-700" aria-hidden="true" />
                    <h3 class="mb-2 text-xl font-semibold text-gray-900">{{ __('pub_theme::services.tasks.track_title') }}</h3>
                    <p class="mb-5 text-gray-700">{{ __('pub_theme::services.tasks.track_body') }}</p>
                    <a href="{{ \Mcamara\LaravelLocalization\Facades\LaravelLocalization::localizeURL('/tickets/track') }}" class="inline-flex min-h-11 items-center font-semibold text-primary-700 underline underline-offset-4 focus:outline-none focus:ring-2 focus:ring-primary-700">{{ __('pub_theme::services.tasks.track_action') }} <span aria-hidden="true" class="ml-2">→</span></a>
                </article>
            </div>

            <p x-show="resultCount === 0" x-cloak class="mt-8 rounded-lg border border-gray-300 bg-gray-50 p-5 text-gray-800" role="status">{{ __('pub_theme::services.results.empty') }}</p>
        </section>

        <section id="categories" class="border-t border-gray-200 bg-gray-50 py-12" aria-labelledby="categories-title">
            <div class="container-italia mx-auto px-4">
                <div class="mb-8 max-w-3xl">
                    <h2 id="categories-title" class="mb-3 text-3xl font-bold text-gray-900">{{ __('pub_theme::services.categories.title') }}</h2>
                    <p class="text-lg text-gray-700">{{ __('pub_theme::services.categories.subtitle') }}</p>
                </div>
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
                    <a href="{{ \Mcamara\LaravelLocalization\Facades\LaravelLocalization::localizeURL('/lista-categorie#reports') }}" class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm transition hover:border-primary-600 focus:outline-none focus:ring-2 focus:ring-primary-700">
                        <h3 class="mb-2 text-lg font-semibold text-gray-900">{{ __('pub_theme::services.categories.items.reports.title') }}</h3>
                        <p class="mb-4 text-gray-700">{{ __('pub_theme::services.categories.items.reports.description') }}</p>
                        <span class="font-semibold text-primary-700 underline underline-offset-4">{{ __('pub_theme::services.tasks.browse_action') }} <span aria-hidden="true">→</span></span>
                    </a>
                </div>
            </div>
        </section>

    </div>
</x-layouts.app>
