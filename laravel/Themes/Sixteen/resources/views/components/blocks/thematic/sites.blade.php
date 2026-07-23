@props(['title' => '', 'items' => []])

{{-- Thematic Sites - Bootstrap Italia Style --}}
<section class="thematic-sites py-8">
    <div class="container">
        <h2 class="title-xxlarge mb-6">{{ $title ?: 'Siti tematici' }}</h2>
        <div class="row g-4">
            @foreach($items as $item)
            <div class="col-12 col-md-4">
                <div class="thematic-site-card card card-bg h-100 border-start border-4 border-{{ $item['color'] ?? 'primary' }}">
                    <div class="card-body p-4">
                        <h3 class="card-title h5 mb-2">{{ $item['title'] }}</h3>
                        <p class="card-text text-muted">{{ $item['description'] }}</p>
                        <a href="{{ $item['url'] }}" class="read-more text-primary fw-semibold text-decoration-none mt-3 d-inline-flex align-items-center">
                            <span>Visita il sito</span>
                            <x-filament::icon icon="heroicon-o-arrow-right" class="icon-sm ms-2" />
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
