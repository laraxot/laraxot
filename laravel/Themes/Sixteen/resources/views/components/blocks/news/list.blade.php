{{--
    News List Block
    Reference: design-comuni-pagine-statiche novita page
--}}
@props([
    'items' => [],
])

<section class="section section-muted pt-4 pb-5">
    <div class="container">
        <div class="row g-4">
            @forelse($items as $item)
                <div class="col-12 col-md-6 col-lg-4">
                    <article class="card card-teaser card-teaser-image-top shadow-sm rounded h-100">
                        <div class="card-body">
                            @if(!empty($item['category']) || !empty($item['date']))
                                <div class="category-top mb-2">
                                    @if(!empty($item['category']))
                                        <span class="badge bg-primary">{{ $item['category'] }}</span>
                                    @endif
                                    @if(!empty($item['date']))
                                        <span class="data ms-2">{{ $item['date'] }}</span>
                                    @endif
                                </div>
                            @endif
                            
                            <h3 class="card-title h5 mb-2">
                                <a href="{{ $item['url'] ?? '#' }}" class="text-decoration-none">
                                    {{ $item['title'] ?? '' }}
                                </a>
                            </h3>
                            
                            @if(!empty($item['excerpt']))
                                <p class="card-text text-muted mb-3">{{ $item['excerpt'] }}</p>
                            @endif
                            
                            <a class="read-more" href="{{ $item['url'] ?? '#' }}">
                                <span class="text">Leggi tutto</span>
                                <svg class="icon"><use href="#it-arrow-right"></use></svg>
                            </a>
                        </div>
                    </article>
                </div>
            @empty
                <div class="col-12">
                    <p class="text-muted text-center">Nessuna novità disponibile</p>
                </div>
            @endforelse
        </div>
    </div>
</section>