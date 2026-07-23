@props(['search_placeholder' => '', 'feedback_question' => '', 'quick_links' => []])

{{-- Search + Feedback Section - Bootstrap Italia Style --}}
<section class="search-feedback-section py-8">
    <div class="container">
        <div class="row g-4">
            {{-- Search --}}
            <div class="col-lg-6">
                <div class="search-box p-4 border rounded">
                    <h3 class="h5 mb-3">CERCA</h3>
                    <form action="#" method="get">
                        <div class="mb-3">
                            <label for="search-input" class="form-label visually-hidden">Cerca nel sito</label>
                            <input type="text" class="form-control" id="search-input" placeholder="{{ $search_placeholder ?: 'Cerca una parola chiave' }}">
                        </div>
                        <button type="submit" class="btn btn-primary">Cerca</button>
                    </form>
                    
                    @if(count($quick_links) > 0)
                    <div class="mt-4">
                        <h4 class="h6 mb-2">Link utili</h4>
                        <ul class="list-unstyled mb-0">
                            @foreach($quick_links as $link)
                            <li>
                                <a href="{{ $link['url'] }}" class="text-decoration-none">
                                    {{ $link['label'] }}
                                </a>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                    @endif
                </div>
            </div>
            
            {{-- Feedback Rating --}}
            <div class="col-lg-6">
                <div class="feedback-box p-4 border rounded">
                    <h3 class="h5 mb-3">{{ $feedback_question ?: 'Quanto sono chiare le informazioni su questa pagina?' }}</h3>
                    <div class="rating-stars mb-3">
                        <div class="btn-group" role="group" aria-label="Valutazione">
                            @for($i = 1; $i <= 5; $i++)
                            <button type="button" class="btn btn-outline-warning btn-sm" data-rating="{{ $i }}">
                                <svg class="icon icon-sm">
                                    <use href="#it-star"></use>
                                </svg>
                            </button>
                            @endfor
                        </div>
                    </div>
                    <p class="text-muted small">Aiutaci a migliorare il servizio</p>
                </div>
            </div>
        </div>
    </div>
</section>
