{{--
    Resources List Block
    Reference: design-comuni-pagine-statiche documenti-dati page
--}}
@props([
    'resources' => [],
    'title' => '',
])

<section class="section py-4">
    <div class="container">
        @if(!empty($title))
            <div class="row mb-4">
                <div class="col-12">
                    <h2>{{ $title }}</h2>
                </div>
            </div>
        @endif
        
        <div class="row g-4">
            @forelse($resources as $resource)
                <div class="col-12 col-md-6 col-lg-4">
                    <article class="card card-teaser shadow-sm rounded h-100">
                        <div class="card-body">
                            @if(!empty($resource['type']))
                                <span class="badge bg-secondary mb-2">{{ $resource['type'] }}</span>
                            @endif
                            
                            <h3 class="card-title h5 mb-2">
                                <a href="{{ $resource['url'] ?? '#' }}" class="text-decoration-none">
                                    {{ $resource['title'] ?? '' }}
                                </a>
                            </h3>
                            
                            @if(!empty($resource['description']))
                                <p class="card-text text-muted mb-2">{{ $resource['description'] }}</p>
                            @endif
                            
                            @if(!empty($resource['size']))
                                <p class="card-text text-muted small mb-0">
                                    <svg class="icon icon-sm me-1" aria-hidden="true">
                                        <use href="#it-file"></use>
                                    </svg>
                                    {{ $resource['size'] }}
                                </p>
                            @endif
                            
                            @if(!empty($resource['url']))
                            <a class="read-more mt-3" href="{{ $resource['url'] }}">
                                <span class="text">Scarica</span>
                                <svg class="icon"><use href="#it-download"></use></svg>
                            </a>
                            @endif
                        </div>
                    </article>
                </div>
            @empty
                <div class="col-12">
                    <p class="text-muted text-center">Nessuna risorsa disponibile</p>
                </div>
            @endforelse
        </div>
    </div>
</section>