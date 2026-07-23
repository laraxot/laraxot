@props([
    'title' => '',
    'category' => '',
    'summary' => '',
    'slug' => '',
])

<section class="cmp-hero it-hero-wrapper">
    <div class="container">
        <div class="row">
            <div class="col-12 col-lg-10">
                <div class="it-hero-text-wrapper">
                    @if($category)
                        <span class="hero-eyebrow text-uppercase d-inline-block mb-2 text-primary font-weight-semibold small">
                            {{ $category }}
                        </span>
                    @endif

                    @if($title)
                        <h1 class="hero-title mb-3">
                            {{ $title }}
                        </h1>
                    @endif

                    @if($summary)
                        <p class="hero-subtitle lead text-muted">
                            {{ $summary }}
                        </p>
                    @endif

                    @if($slug)
                        <a href="#{{ $slug }}" class="read-more mt-3 d-inline-flex align-items-center">
                            <span class="text-decoration-none">
                                {{ __('sixteen::common.labels.learn_more.label', ['default' => 'Scopri di più']) }}
                            </span>
                            <x-filament::icon icon="heroicon-o-arrow-down" class="icon icon-sm ms-1" />
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>
