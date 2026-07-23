{{--
    Events List Block
    Reference: design-comuni-pagine-statiche eventi page
--}}
@props([
    'events' => [],
])

<section class="section pt-4 pb-5">
    <div class="container">
        <div class="row g-4">
            @forelse($events as $event)
                <div class="col-12 col-md-6 col-lg-4">
                    <article class="card card-teaser shadow-sm rounded h-100">
                        <div class="card-body">
                            @if(!empty($event['category']))
                                <div class="category-top mb-2">
                                    <span class="badge bg-primary">{{ $event['category'] }}</span>
                                </div>
                            @endif
                            
                            <div class="mb-3">
                                @if(!empty($event['date']))
                                    <span class="data fw-bold text-primary">{{ $event['date'] }}</span>
                                @endif
                                @if(!empty($event['time']))
                                    <span class="text-muted ms-2">{{ $event['time'] }}</span>
                                @endif
                            </div>
                            
                            <h3 class="card-title h5 mb-2">
                                <a href="{{ $event['url'] ?? '#' }}" class="text-decoration-none">
                                    {{ $event['title'] ?? '' }}
                                </a>
                            </h3>
                            
                            @if(!empty($event['location']))
                                <p class="card-text text-muted mb-3">
                                    <svg class="icon icon-sm me-1" aria-hidden="true">
                                        <use href="#it-map-marker"></use>
                                    </svg>
                                    {{ $event['location'] }}
                                </p>
                            @endif
                            
                            <a class="read-more" href="{{ $event['url'] ?? '#' }}">
                                <span class="text">Leggi tutto</span>
                                <svg class="icon"><use href="#it-arrow-right"></use></svg>
                            </a>
                        </div>
                    </article>
                </div>
            @empty
                <div class="col-12">
                    <p class="text-muted text-center">Nessun evento disponibile</p>
                </div>
            @endforelse
        </div>
    </div>
</section>