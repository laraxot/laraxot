@php
    use Themes\Sixteen\Infrastructure\Url\FrontofficeRoutes;

    // Canon FO: solo container0.* via Infrastructure riusabile — mai tickets.* / localizeUrl.
    $serviceUrl = FrontofficeRoutes::ticketsCreate();
    $reportsUrl = FrontofficeRoutes::ticketsIndex();
    $trackUrl = FrontofficeRoutes::ticketsTrack();
@endphp

<div class="bg-slate-50 px-4 py-10 sm:px-6 lg:px-8 lg:py-14">
    <div class="mx-auto max-w-5xl">
        <nav aria-label="{{ __('fixcity::global.breadcrumb') }}">
            <ol class="flex flex-wrap gap-2 text-sm text-slate-700">
                <li><a class="underline underline-offset-4 hover:text-sky-800" href="{{ \Themes\Sixteen\Infrastructure\Url\FrontofficeUrl::fromStoredUrl('/') }}">{{ __('pub_theme::navigation.home') }}</a></li>
                <li aria-hidden="true">/</li>
                <li aria-current="page">{{ __('pub_theme::services.meta.breadcrumb') }}</li>
            </ol>
        </nav>
        <header id="fixcity-services-hero" class="mt-8 max-w-3xl rounded-xl p-6 sm:p-8" style="background-color:#17324d">
            <h1 class="text-3xl font-bold tracking-tight text-white sm:text-4xl" style="color:#fff">{{ __('pub_theme::services.meta.title') }}</h1>
            <p class="mt-3 text-lg leading-8 text-white" style="color:#fff">{{ __('pub_theme::services.meta.description') }}</p>
        </header>
        <div id="service-catalogue" class="mt-10 grid gap-5 md:grid-cols-3">
            <a data-service-searchable class="group rounded-xl border border-slate-200 bg-white p-6 shadow-sm transition hover:border-sky-700 hover:shadow-md focus:outline-none focus:ring-4 focus:ring-sky-800/20" href="{{ $serviceUrl }}">
                <h2 class="text-xl font-bold text-slate-950">{{ __('pub_theme::services.nav.report') }}</h2>
                <p class="mt-3 leading-7 text-slate-700">{{ __('pub_theme::services.tasks.report_body') }}</p>
                <span class="mt-5 inline-flex font-semibold text-sky-900 underline underline-offset-4">{{ __('pub_theme::services.tasks.report_action') }}</span>
            </a>
            <a data-service-searchable class="group rounded-xl border border-slate-200 bg-white p-6 shadow-sm transition hover:border-sky-700 hover:shadow-md focus:outline-none focus:ring-4 focus:ring-sky-800/20" href="{{ $reportsUrl }}">
                <h2 class="text-xl font-bold text-slate-950">{{ __('pub_theme::services.nav.browse') }}</h2>
                <p class="mt-3 leading-7 text-slate-700">{{ __('pub_theme::services.tasks.browse_body') }}</p>
                <span class="mt-5 inline-flex font-semibold text-sky-900 underline underline-offset-4">{{ __('pub_theme::services.tasks.browse_action') }}</span>
            </a>
            <a data-service-searchable class="group rounded-xl border border-slate-200 bg-white p-6 shadow-sm transition hover:border-sky-700 hover:shadow-md focus:outline-none focus:ring-4 focus:ring-sky-800/20" href="{{ $trackUrl }}">
                <h2 class="text-xl font-bold text-slate-950">{{ __('pub_theme::services.nav.track') }}</h2>
                <p class="mt-3 leading-7 text-slate-700">{{ __('pub_theme::services.tasks.track_body') }}</p>
                <span class="mt-5 inline-flex font-semibold text-sky-900 underline underline-offset-4">{{ __('pub_theme::services.tasks.track_action') }}</span>
            </a>
        </div>
    </div>
</div>
