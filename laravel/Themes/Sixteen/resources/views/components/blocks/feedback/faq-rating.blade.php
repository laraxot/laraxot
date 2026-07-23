@props(['data' => []])

@php
    $title = $data['title'] ?? 'Quanto sono chiare le informazioni su questa pagina?';
    $subtitle = $data['subtitle'] ?? 'Grazie, il tuo parere ci aiuterà a migliorare il servizio!';
@endphp

<div class="bg-primary">
    <div class="container">
        <div class="row d-flex justify-content-center bg-primary">
            <div class="col-12 col-lg-6">
                <div class="cmp-rating pt-lg-80 pb-lg-80" id="rating">
                    <div class="card shadow card-wrapper" data-element="feedback">
                        <div class="cmp-rating__card-first">
                            <div class="card-header border-0">
                                <h2 class="title-medium-2-semi-bold mb-0" data-element="feedback-title">{{ $title }}</h2>
                            </div>
                            <div class="card-body">
                                <div class="faq-rating-stars" aria-label="Valuta da 1 a 5 stelle la pagina">
                                    @for ($star = 1; $star <= 5; $star++)
                                        <button type="button" class="faq-rating-star" aria-label="Valuta {{ $star }} stelle su 5">★</button>
                                    @endfor
                                </div>
                                <p class="faq-rating-help mb-0">{{ $subtitle }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
