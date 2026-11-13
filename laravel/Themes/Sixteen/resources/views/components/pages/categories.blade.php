@php($publicCategories = app(\Modules\Fixcity\Actions\ListPublicCategoriesAction::class)->execute())

<div id="categories-content" class="container-italia mx-auto px-4 py-10" aria-labelledby="categories-title">
    {{-- Briciole di pane del riferimento (le classi flex di prima non arrivavano al CSS compilato e impilavano le voci: DCP-288, FO-1-28). --}}
    @include('pub_theme::components.blocks.content-page.partials.breadcrumb', ['breadcrumb' => [
        'items' => [['label' => __('pub_theme::service_report.breadcrumb.home'), 'url' => \Themes\Sixteen\Infrastructure\Url\FrontofficeUrl::fromStoredUrl('/')]],
        'current' => __('pub_theme::services.categories.title'),
        'class' => 'mb-8',
    ]])

    <div class="mb-8 max-w-3xl">
        <h1 id="categories-title" class="mb-3 text-4xl font-bold text-gray-900">{{ __('pub_theme::services.categories.title') }}</h1>
        <p class="text-xl text-gray-700">{{ __('pub_theme::services.categories.subtitle') }}</p>
    </div>

    <div class="mb-10 flex flex-wrap gap-4" aria-label="{{ __('pub_theme::services.categories.actions.aria') }}">
        <a href="{{ \Themes\Sixteen\Infrastructure\Url\FrontofficeRoutes::ticketsCreate() }}" class="inline-flex min-h-11 items-center rounded-md bg-primary-700 px-5 py-3 font-semibold text-white focus:outline-none focus:ring-2 focus:ring-primary-700 focus:ring-offset-2">
            {{ __('pub_theme::services.categories.actions.report') }}
        </a>
        <a href="{{ \Themes\Sixteen\Infrastructure\Url\FrontofficeRoutes::ticketsIndex() }}" class="inline-flex min-h-11 items-center rounded-md border border-primary-700 px-5 py-3 font-semibold text-primary-700 underline underline-offset-4 focus:outline-none focus:ring-2 focus:ring-primary-700 focus:ring-offset-2">
            {{ __('pub_theme::services.categories.actions.browse') }}
        </a>
    </div>

    <section id="category-directory" aria-labelledby="category-directory-title">
        <h2 id="category-directory-title" class="mb-5 text-2xl font-bold text-gray-900">{{ __('pub_theme::services.categories.directory.title') }}</h2>

        @if($publicCategories->isEmpty())
            <p class="rounded-xl border border-gray-200 bg-white p-6 text-gray-700">{{ __('pub_theme::services.categories.directory.empty') }}</p>
        @else
            <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-3">
                @foreach($publicCategories as $category)
                    <article id="category-{{ $category->id }}" class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
                        <h3 class="mb-2 text-xl font-semibold text-gray-900">{{ $category->name }}</h3>
                        <p class="mb-4 text-gray-700">{{ $category->description }}</p>
                        @if($category->children->isNotEmpty())
                            <ul class="space-y-1 text-sm text-gray-600" aria-label="{{ __('pub_theme::services.categories.directory.subcategories') }}">
                                @foreach($category->children as $child)
                                    <li>{{ $child->name }}</li>
                                @endforeach
                            </ul>
                        @endif
                    </article>
                @endforeach
            </div>
        @endif
    </section>

    <section id="reports" class="mt-10 rounded-xl border border-gray-200 bg-white p-6 shadow-sm" aria-labelledby="reports-title">
        <h2 id="reports-title" class="mb-2 text-xl font-semibold text-gray-900">{{ __('pub_theme::services.categories.items.reports.title') }}</h2>
        <p class="mb-5 text-gray-700">{{ __('pub_theme::services.categories.items.reports.description') }}</p>
        <a href="{{ \Themes\Sixteen\Infrastructure\Url\FrontofficeRoutes::ticketsIndex() }}" class="font-semibold text-primary-700 underline underline-offset-4 focus:outline-none focus:ring-2 focus:ring-primary-700">
            {{ __('pub_theme::services.tasks.browse_action') }} <span aria-hidden="true">→</span>
        </a>
    </section>
</div>
