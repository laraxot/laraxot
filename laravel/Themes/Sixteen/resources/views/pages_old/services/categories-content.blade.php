<main id="main-content" class="container-italia mx-auto px-4 py-10" aria-labelledby="categories-title">
    <nav class="mb-8 text-sm" aria-label="{{ __('pub_theme::service_report.breadcrumb.aria') }}">
        <ol class="flex flex-wrap items-center gap-2 text-gray-700">
            <li><a class="underline" href="{{ \Mcamara\LaravelLocalization\Facades\LaravelLocalization::localizeURL('/') }}">{{ __('pub_theme::service_report.breadcrumb.home') }}</a></li>
            <li aria-hidden="true">/</li>
            <li aria-current="page">{{ __('pub_theme::services.categories.title') }}</li>
        </ol>
    </nav>

    <div class="mb-8 max-w-3xl">
        <h1 id="categories-title" class="mb-3 text-4xl font-bold text-gray-900">{{ __('pub_theme::services.categories.title') }}</h1>
        <p class="text-xl text-gray-700">{{ __('pub_theme::services.categories.subtitle') }}</p>
    </div>

    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-3">
        <article id="reports" class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
            <h2 class="mb-2 text-xl font-semibold text-gray-900">{{ __('pub_theme::services.categories.items.reports.title') }}</h2>
            <p class="mb-5 text-gray-700">{{ __('pub_theme::services.categories.items.reports.description') }}</p>
            <a href="{{ \Mcamara\LaravelLocalization\Facades\LaravelLocalization::localizeURL('/tickets') }}" class="font-semibold text-primary-700 underline underline-offset-4 focus:outline-none focus:ring-2 focus:ring-primary-700">
                {{ __('pub_theme::services.tasks.browse_action') }} <span aria-hidden="true">→</span>
            </a>
        </article>
    </div>
</main>
